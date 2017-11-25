<?php
/*+********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/
global $calpath;
global $app_strings,$mod_strings;
global $theme;
global $log;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once('data/CRMEntity.php');
require_once("modules/Reports/Reports.php");
require_once 'modules/Reports/ReportUtils.php';
require_once("vtlib/Ncrm/Module.php");
require_once('modules/Ncrm/helpers/Util.php');
require_once('include/RelatedListView.php');

/*
 * Helper class to determine the associative dependency between tables.
 */
class ReportRunQueryDependencyMatrix {
	protected $matrix = array();
	protected $computedMatrix = null;

	function setDependency($table, array $dependents) {
		$this->matrix[$table] = $dependents;
	}

	function addDependency($table, $dependent) {
		if (isset($this->matrix[$table]) && !in_array($dependent, $this->matrix[$table])) {
			$this->matrix[$table][] = $dependent;
		} else {
			$this->setDependency($table, array($dependent));
		}
	}

	function getDependents($table) {
		$this->computeDependencies();
		return isset($this->computedMatrix[$table])? $this->computedMatrix[$table] : array();
	}

	protected function computeDependencies() {
		if ($this->computedMatrix !== null) return;

		$this->computedMatrix = array();
		foreach ($this->matrix as $key => $values) {
			$this->computedMatrix[$key] =
				$this->computeDependencyForKey($key, $values);
		}
	}
	protected function computeDependencyForKey($key, $values) {
		$merged = array();
		foreach ($values as $value) {
			$merged[] = $value;
			if (isset($this->matrix[$value])) {
				$merged = array_merge($merged, $this->matrix[$value]);
			}
		}
		return $merged;
	}
}

class ReportRunQueryPlanner {
	// Turn-off the query planning to revert back - backward compatiblity
	protected $disablePlanner = false;

	protected $tables = array();
	protected $tempTables = array();
	protected $tempTablesInitialized = false;

	// Turn-off in case the query result turns-out to be wrong.
	protected $allowTempTables = true;
	protected $tempTablePrefix = 'ncrm_reptmptbl_';
	protected static $tempTableCounter   = 0;
	protected $registeredCleanup = false;


        function addTable($table) {
            if(!empty($table))
                $this->tables[$table] = $table;
	}

	function requireTable($table, $dependencies=null) {

		if ($this->disablePlanner) {
			return true;
		}

		if (isset($this->tables[$table])) {
			return true;
		}
		if (is_array($dependencies)) {
			foreach ($dependencies as $dependentTable) {
				if (isset($this->tables[$dependentTable])) {
					return true;
				}
			}
		} else if ($dependencies instanceof ReportRunQueryDependencyMatrix) {
			$dependents = $dependencies->getDependents($table);
			if ($dependents) {
				return count(array_intersect($this->tables, $dependents)) > 0;
			}
		}
		return false;
	}

	function getTables() {
		return $this->tables;
	}

	function newDependencyMatrix() {
		return new ReportRunQueryDependencyMatrix();
	}

	function registerTempTable($query, $keyColumns) {
		if ($this->allowTempTables && !$this->disablePlanner) {
			global $current_user;

			$keyColumns = is_array($keyColumns)? array_unique($keyColumns) : array($keyColumns);

			// Minor optimization to avoid re-creating similar temporary table.
			$uniqueName = NULL;
			foreach ($this->tempTables as $tmpUniqueName => $tmpTableInfo) {
				if (strcasecmp($query, $tmpTableInfo['query']) === 0) {
					// Capture any additional key columns
					$tmpTableInfo['keycolumns'] = array_unique(array_merge($tmpTableInfo['keycolumns'], $keyColumns));
					$uniqueName = $tmpUniqueName;
					break;
				}
			}

			// Nothing found?
			if ($uniqueName === NULL) {
			// TODO Adding randomness in name to avoid concurrency
			// even when same-user opens the report multiple instances at same-time.
			$uniqueName = $this->tempTablePrefix .
					str_replace('.', '', uniqid($current_user->id , true)) . (self::$tempTableCounter++);

			$this->tempTables[$uniqueName] = array(
				'query' => $query,
					'keycolumns' => is_array($keyColumns)? array_unique($keyColumns) : array($keyColumns),
				);
			}

			return $uniqueName;
		}
		return "($query)";
	}

	function initializeTempTables() {
		global $adb;

		$oldDieOnError = $adb->dieOnError;
		$adb->dieOnError = false; // If query planner is re-used there could be attempt for temp table...
		foreach ($this->tempTables as $uniqueName => $tempTableInfo) {
			$query1 = sprintf('CREATE TEMPORARY TABLE %s AS %s', $uniqueName, $tempTableInfo['query']);
			$adb->pquery($query1, array());

			$keyColumns = $tempTableInfo['keycolumns'];
			foreach ($keyColumns as $keyColumn) {
				$query2 = sprintf('ALTER TABLE %s ADD INDEX (%s)', $uniqueName, $keyColumn);
			$adb->pquery($query2, array());
			}
		}

		$adb->dieOnError = $oldDieOnError;

		// Trigger cleanup of temporary tables when the execution of the request ends.
		// NOTE: This works better than having in __destruct
		// (as the reference to this object might end pre-maturely even before query is executed)
		if (!$this->registeredCleanup) {
			register_shutdown_function(array($this, 'cleanup'));
			// To avoid duplicate registration on this instance.
			$this->registeredCleanup = true;
		}

	}

	function cleanup() {
		global $adb;

		$oldDieOnError = $adb->dieOnError;
		$adb->dieOnError = false; // To avoid abnormal termination during shutdown...
		foreach ($this->tempTables as $uniqueName => $tempTableInfo) {
			$adb->pquery('DROP TABLE ' . $uniqueName, array());
		}
		$adb->dieOnError = $oldDieOnError;

		$this->tempTables = array();
	}
}

class ReportRun extends CRMEntity
{
	// Maximum rows that should be emitted in HTML view.
	static $HTMLVIEW_MAX_ROWS = 1000;

	var $reportid;
	var $primarymodule;
	var $secondarymodule;
	var $orderbylistsql;
	var $orderbylistcolumns;

	var $selectcolumns;
	var $groupbylist;
	var $reporttype;
	var $reportname;
	var $totallist;

	var $_groupinglist  = false;
	var $_columnslist    = false;
	var $_stdfilterlist = false;
	var $_columnstotallist = false;
	var $_advfiltersql = false;

    // All UItype 72 fields are added here so that in reports the values are append currencyId::value
	var $append_currency_symbol_to_value = array('Products_Unit_Price','Services_Price',
						'Invoice_Total', 'Invoice_Sub_Total', 'Invoice_Pre_Tax_Total', 'Invoice_S&H_Amount', 'Invoice_Discount_Amount', 'Invoice_Adjustment',
						'Quotes_Total', 'Quotes_Sub_Total', 'Quotes_Pre_Tax_Total', 'Quotes_S&H_Amount', 'Quotes_Discount_Amount', 'Quotes_Adjustment',
						'SalesOrder_Total', 'SalesOrder_Sub_Total', 'SalesOrder_Pre_Tax_Total', 'SalesOrder_S&H_Amount', 'SalesOrder_Discount_Amount', 'SalesOrder_Adjustment',
						'PurchaseOrder_Total', 'PurchaseOrder_Sub_Total', 'PurchaseOrder_Pre_Tax_Total', 'PurchaseOrder_S&H_Amount', 'PurchaseOrder_Discount_Amount', 'PurchaseOrder_Adjustment',
                        'Invoice_Received','PurchaseOrder_Paid','Invoice_Balance','PurchaseOrder_Balance'
						);
	var $ui10_fields = array();
	var $ui101_fields = array();
	var $groupByTimeParent = array( 'Quarter'=>array('Year'),
									'Month'=>array('Year')
								);

	var $queryPlanner = null;


    protected static $instances = false;
	// Added to support line item fields calculation, if line item fields
	// are selected then module fields cannot be selected and vice versa
	var $lineItemFieldsInCalculation = false;

        /** Function to set reportid,primarymodule,secondarymodule,reporttype,reportname, for given reportid
	 *  This function accepts the $reportid as argument
	 *  It sets reportid,primarymodule,secondarymodule,reporttype,reportname for the given reportid
         *  To ensure single-instance is present for $reportid
         *  as we optimize using ReportRunPlanner and setup temporary tables.
	 */

        function ReportRun($reportid)
	{
		$oReport = new Reports($reportid);
		$this->reportid = $reportid;
		$this->primarymodule = $oReport->primodule;
		$this->secondarymodule = $oReport->secmodule;
		$this->reporttype = $oReport->reporttype;
		$this->reportname = $oReport->reportname;
		$this->queryPlanner = new ReportRunQueryPlanner();
	}

        public static function getInstance($reportid) {
            if (!isset(self::$instances[$reportid])) {
                self::$instances[$reportid] = new ReportRun($reportid);
            }
            return self::$instances[$reportid];
        }

	/** Function to get the columns for the reportid
	 *  This function accepts the $reportid and $outputformat (optional)
	 *  This function returns  $columnslist Array($tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname As Header value,
	 *					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 As Header value,
	 *					      					|
 	 *					      $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen As Header value
	 *				      	     )
	 *
	 */
	function getQueryColumnsList($reportid,$outputformat='')
	{
		// Have we initialized information already?
		if($this->_columnslist !== false) {
			return $this->_columnslist;
		}

		global $adb;
		global $modules;
		global $log,$current_user,$current_language;
		$ssql = "select ncrm_selectcolumn.* from ncrm_report inner join ncrm_selectquery on ncrm_selectquery.queryid = ncrm_report.queryid";
		$ssql .= " left join ncrm_selectcolumn on ncrm_selectcolumn.queryid = ncrm_selectquery.queryid";
		$ssql .= " where ncrm_report.reportid = ?";
		$ssql .= " order by ncrm_selectcolumn.columnindex";
		$result = $adb->pquery($ssql, array($reportid));
		$permitted_fields = Array();

		while($columnslistrow = $adb->fetch_array($result))
		{
			$fieldname ="";
			$fieldcolname = $columnslistrow["columnname"];
			list($tablename,$colname,$module_field,$fieldname,$single) = split(":",$fieldcolname);
			list($module,$field) = split("_",$module_field,2);
			$inventory_fields = array('serviceid');
			$inventory_modules = getInventoryModules();
			require('user_privileges/user_privileges_'.$current_user->id.'.php');
			if(sizeof($permitted_fields[$module]) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
			{
				$permitted_fields[$module] = $this->getaccesfield($module);
			}
			if(in_array($module,$inventory_modules)){
				if (!empty ($permitted_fields)) {
					foreach ($inventory_fields as $value) {
						array_push($permitted_fields[$module], $value);
					}
				}
			}
			$selectedfields = explode(":",$fieldcolname);
			if($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1
					&& !in_array($selectedfields[3], $permitted_fields[$module])) {
				//user has no access to this field, skip it.
				continue;
			}
			$querycolumns = $this->getEscapedColumns($selectedfields);
			if(isset($module) && $module!="") {
				$mod_strings = return_module_language($current_language,$module);
			}

			$targetTableName = $tablename;

			$fieldlabel = trim(preg_replace("/$module/"," ",$selectedfields[2],1));
			$mod_arr=explode('_',$fieldlabel);
			$fieldlabel = trim(str_replace("_"," ",$fieldlabel));
			//modified code to support i18n issue
			$fld_arr = explode(" ",$fieldlabel);
			if(($mod_arr[0] == '')) {
				$mod = $module;
				$mod_lbl = getTranslatedString($module,$module); //module
			} else {
				$mod = $mod_arr[0];
				array_shift($fld_arr);
				$mod_lbl = getTranslatedString($fld_arr[0],$mod); //module
			}
			$fld_lbl_str = implode(" ",$fld_arr);
			$fld_lbl = getTranslatedString($fld_lbl_str,$module); //fieldlabel
			$fieldlabel = $mod_lbl." ".$fld_lbl;
			if(($selectedfields[0] == "ncrm_usersRel1")  && ($selectedfields[1] == 'user_name') && ($selectedfields[2] == 'Quotes_Inventory_Manager')){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>$selectedfields[0].".first_name",'last_name'=>$selectedfields[0].".last_name"), 'Users');
				$columnslist[$fieldcolname] = "trim( $concatSql ) as ".$module."_Inventory_Manager";
                                $this->queryPlanner->addTable($selectedfields[0]);
				continue;
			}
			if((CheckFieldPermission($fieldname,$mod) != 'true' && $colname!="crmid" && (!in_array($fieldname,$inventory_fields) && in_array($module,$inventory_modules))) || empty($fieldname))
			{
				continue;
			}
			else
			{
				$this->labelMapping[$selectedfields[2]] = str_replace(" ","_",$fieldlabel);

				// To check if the field in the report is a custom field
				// and if yes, get the label of this custom field freshly from the ncrm_field as it would have been changed.
				// Asha - Reference ticket : #4906

				if($querycolumns == "") {
					$columnslist[$fieldcolname] =  $this->getColumnSQL($selectedfields);
				} else {
					$columnslist[$fieldcolname] = $querycolumns;
				}

				$this->queryPlanner->addTable($targetTableName);
			}
		}

		if ($outputformat == "HTML" || $outputformat == "PDF" || $outputformat == "PRINT") {
            $columnslist['ncrm_crmentity:crmid:LBL_ACTION:crmid:I'] = 'ncrm_crmentity.crmid AS "'.$this->primarymodule.'_LBL_ACTION"' ;
        }

		// Save the information
		$this->_columnslist = $columnslist;

		$log->info("ReportRun :: Successfully returned getQueryColumnsList".$reportid);
		return $columnslist;
	}


	function getColumnSQL($selectedfields) {
		global $adb;
		$header_label = $selectedfields[2]; // Header label to be displayed in the reports table

		list($module,$field) = split("_",$selectedfields[2]);
        $concatSql = getSqlForNameInDisplayFormat(array('first_name'=>$selectedfields[0].".first_name",'last_name'=>$selectedfields[0].".last_name"), 'Users');

        if ($selectedfields[4] == 'C') {
            $field_label_data = split("_", $selectedfields[2]);
            $module = $field_label_data[0];
            if ($module != $this->primarymodule) {
                $columnSQL = "case when (" . $selectedfields[0] . "." . $selectedfields[1] . "='1')then 'yes' else case when (ncrm_crmentity$module.crmid !='') then 'no' else '-' end end AS '".decode_html($selectedfields[2])."'";
                $this->queryPlanner->addTable("ncrm_crmentity$module");
            } else {
                $columnSQL = "case when (" . $selectedfields[0] . "." . $selectedfields[1] . "='1')then 'yes' else case when (ncrm_crmentity.crmid !='') then 'no' else '-' end end AS '".decode_html($selectedfields[2])."'";
                $this->queryPlanner->addTable($selectedfields[0]);
            }
        } elseif ($selectedfields[4] == 'D' || $selectedfields[4] == 'DT') {
            if ($selectedfields[5] == 'Y') {
                if ($selectedfields[0] == 'ncrm_activity' && $selectedfields[1] == 'date_start') {
                    if($module == 'Emails') {
                        $columnSQL = "YEAR(cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATE)) AS Emails_Date_Sent_Year";
                    }else{
                        $columnSQL = "YEAR(cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATETIME)) AS Calendar_Start_Date_and_Time_Year";
                    }
                } else if ($selectedfields[0] == "ncrm_crmentity" . $this->primarymodule) {
                    $columnSQL = "YEAR(ncrm_crmentity." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Year'";
                } else {
                    $columnSQL = 'YEAR(' . $selectedfields[0] . "." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Year'";
                }
                $this->queryPlanner->addTable($selectedfields[0]);
            } elseif ($selectedfields[5] == 'M') {
                if ($selectedfields[0] == 'ncrm_activity' && $selectedfields[1] == 'date_start') {
                    if($module == 'Emails') {
                        $columnSQL = "MONTHNAME(cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATE)) AS Emails_Date_Sent_Month";
                    }else{
                        $columnSQL = "MONTHNAME(cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATETIME)) AS Calendar_Start_Date_and_Time_Month";
                    }
                } else if ($selectedfields[0] == "ncrm_crmentity" . $this->primarymodule) {
                    $columnSQL = "MONTHNAME(ncrm_crmentity." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Month'";
                } else {
                    $columnSQL = 'MONTHNAME(' . $selectedfields[0] . "." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Month'";
                }
                $this->queryPlanner->addTable($selectedfields[0]);
            } elseif ($selectedfields[5] == 'MY') {	// used in charts to get the year also, which will be used for click throughs
                if ($selectedfields[0] == 'ncrm_activity' && $selectedfields[1] == 'date_start') {
                    if($module == 'Emails') {
                        $columnSQL = "date_format(cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATE), '%M %Y') AS Emails_Date_Sent_Month";
                    }else{
                        $columnSQL = "date_format(cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATETIME), '%M %Y') AS Calendar_Start_Date_and_Time_Month";
                    }
                } else if ($selectedfields[0] == "ncrm_crmentity" . $this->primarymodule) {
                    $columnSQL = "date_format(ncrm_crmentity." . $selectedfields[1] . ", '%M %Y') AS '" . decode_html($header_label) . "_Month'";
                } else {
                    $columnSQL = 'date_format(' . $selectedfields[0] . "." . $selectedfields[1] . ", '%M %Y') AS '" . decode_html($header_label) . "_Month'";
                }
                $this->queryPlanner->addTable($selectedfields[0]);
            } else {
                if ($selectedfields[0] == 'ncrm_activity' && $selectedfields[1] == 'date_start') {
                    if($module == 'Emails') {
                        $columnSQL = "cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATE) AS Emails_Date_Sent";
                    }else{
                    	$columnSQL = "cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATETIME) AS Calendar_Start_Date_and_Time";
                    }
                } else if ($selectedfields[0] == "ncrm_crmentity" . $this->primarymodule) {
                    $columnSQL = "ncrm_crmentity." . $selectedfields[1] . " AS '" . decode_html($header_label) . "'";
                } else {
                    $userformat=str_replace(array("dd-mm-yyyy","mm-dd-yyyy","yyyy-mm-dd"),array("%d-%m-%Y","%m-%d-%Y","%Y-%m-%d"),$current_user->date_format);
                    $columnSQL = "date_format (" . $selectedfields[0] . "." . $selectedfields[1] . ",'$userformat') AS '" . decode_html($header_label) . "'";
                }
                $this->queryPlanner->addTable($selectedfields[0]);
            }
        } elseif ($selectedfields[0] == 'ncrm_activity' && $selectedfields[1] == 'status') {
            $columnSQL = " case when (ncrm_activity.status not like '') then ncrm_activity.status else ncrm_activity.eventstatus end AS Calendar_Status";
        } elseif($selectedfields[0] == 'ncrm_activity' && $selectedfields[1] == 'date_start') {
			if($module == 'Emails') {
				$columnSQL = "cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATE) AS Emails_Date_Sent";
			} else {
				$columnSQL = "cast(concat(ncrm_activity.date_start,'  ',ncrm_activity.time_start) as DATETIME) AS Calendar_Start_Date_and_Time";
			}
		} elseif(stristr($selectedfields[0],"ncrm_users") && ($selectedfields[1] == 'user_name')) {
			$temp_module_from_tablename = str_replace("ncrm_users","",$selectedfields[0]);
			if($module != $this->primarymodule) {
				$condition = "and ncrm_crmentity".$module.".crmid!=''";
				$this->queryPlanner->addTable("ncrm_crmentity$module");
			} else {
				$condition = "and ncrm_crmentity.crmid!=''";
			}
			if($temp_module_from_tablename == $module) {
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>$selectedfields[0].".first_name",'last_name'=>$selectedfields[0].".last_name"), 'Users');
				$columnSQL = " case when(".$selectedfields[0].".last_name NOT LIKE '' $condition ) THEN ".$concatSql." else ncrm_groups".$module.".groupname end AS '".decode_html($header_label)."'";
				$this->queryPlanner->addTable('ncrm_groups'.$module); // Auto-include the dependent module table.
			} else {//Some Fields can't assigned to groups so case avoided (fields like inventory manager)
				$columnSQL = $selectedfields[0].".user_name AS '".decode_html($header_label)."'";
			}
			$this->queryPlanner->addTable($selectedfields[0]);
		} elseif(stristr($selectedfields[0],"ncrm_crmentity") && ($selectedfields[1] == 'modifiedby')) {
			$targetTableName = 'ncrm_lastModifiedBy'.$module;
			$concatSql = getSqlForNameInDisplayFormat(array('last_name'=>$targetTableName.'.last_name', 'first_name'=>$targetTableName.'.first_name'), 'Users');
			$columnSQL = "trim($concatSql) AS $header_label";
			$this->queryPlanner->addTable("ncrm_crmentity$module");
			$this->queryPlanner->addTable($targetTableName);

			// Added when no fields from the secondary module is selected but lastmodifiedby field is selected
			$moduleInstance = CRMEntity::getInstance($module);
			$this->queryPlanner->addTable($moduleInstance->table_name);
		} else if(stristr($selectedfields[0],"ncrm_crmentity") && ($selectedfields[1] == 'smcreatorid')){
            $targetTableName = 'ncrm_createdby'.$module;
            $concatSql = getSqlForNameInDisplayFormat(array('last_name'=>$targetTableName.'.last_name', 'first_name'=>$targetTableName.'.first_name'), 'Users');
			$columnSQL = "trim($concatSql) AS ".decode_html($header_label)."";
			$this->queryPlanner->addTable("ncrm_crmentity$module");
			$this->queryPlanner->addTable($targetTableName);

            // Added when no fields from the secondary module is selected but creator field is selected
			$moduleInstance = CRMEntity::getInstance($module);
			$this->queryPlanner->addTable($moduleInstance->table_name);
		} elseif($selectedfields[0] == "ncrm_crmentity".$this->primarymodule) {
			$columnSQL = "ncrm_crmentity.".$selectedfields[1]." AS '".decode_html($header_label)."'";
		} elseif($selectedfields[0] == 'ncrm_products' && $selectedfields[1] == 'unit_price') {
			$columnSQL = "concat(".$selectedfields[0].".currency_id,'::',innerProduct.actual_unit_price) AS '". decode_html($header_label) ."'";
			$this->queryPlanner->addTable("innerProduct");
		} elseif(in_array($selectedfields[2], $this->append_currency_symbol_to_value)) {
			if($selectedfields[1] == 'discount_amount') {
				$columnSQL = "CONCAT(".$selectedfields[0].".currency_id,'::', IF(".$selectedfields[0].".discount_amount != '',".$selectedfields[0].".discount_amount, (".$selectedfields[0].".discount_percent/100) * ".$selectedfields[0].".subtotal)) AS ".decode_html($header_label);
			} else {
				$columnSQL = "concat(".$selectedfields[0].".currency_id,'::',".$selectedfields[0].".".$selectedfields[1].") AS '" . decode_html($header_label) ."'";
			}
		} elseif($selectedfields[0] == 'ncrm_notes' && ($selectedfields[1] == 'filelocationtype' || $selectedfields[1] == 'filesize' || $selectedfields[1] == 'folderid' || $selectedfields[1]=='filestatus')) {
			if($selectedfields[1] == 'filelocationtype'){
				$columnSQL = "case ".$selectedfields[0].".".$selectedfields[1]." when 'I' then 'Internal' when 'E' then 'External' else '-' end AS '".decode_html($selectedfields[2])."'";
			} else if($selectedfields[1] == 'folderid'){
				$columnSQL = "ncrm_attachmentsfolder.foldername AS '$selectedfields[2]'";
				$this->queryPlanner->addTable("ncrm_attachmentsfolder");
			} elseif($selectedfields[1] == 'filestatus'){
				$columnSQL = "case ".$selectedfields[0].".".$selectedfields[1]." when '1' then 'yes' when '0' then 'no' else '-' end AS '". decode_html($selectedfields[2]) ."'";
			} elseif($selectedfields[1] == 'filesize'){
				$columnSQL = "case ".$selectedfields[0].".".$selectedfields[1]." when '' then '-' else concat(".$selectedfields[0].".".$selectedfields[1]."/1024,'  ','KB') end AS '".decode_html($selectedfields[2])."'";
			}
		} elseif($selectedfields[0] == 'ncrm_inventoryproductrel') {
			if($selectedfields[1] == 'discount_amount'){
				$columnSQL = " case when (ncrm_inventoryproductrel{$module}.discount_amount != '') then ncrm_inventoryproductrel{$module}.discount_amount else ROUND((ncrm_inventoryproductrel{$module}.listprice * ncrm_inventoryproductrel{$module}.quantity * (ncrm_inventoryproductrel{$module}.discount_percent/100)),3) end AS '" . decode_html($header_label) ."'";
				$this->queryPlanner->addTable($selectedfields[0].$module);
			} else if($selectedfields[1] == 'productid'){
				$columnSQL = "ncrm_products{$module}.productname AS '" . decode_html($header_label) ."'";
				$this->queryPlanner->addTable("ncrm_products{$module}");
			} else if($selectedfields[1] == 'serviceid'){
				$columnSQL = "ncrm_service{$module}.servicename AS '" . decode_html($header_label) ."'";
				$this->queryPlanner->addTable("ncrm_service{$module}");
			} else if($selectedfields[1] == 'listprice') {
				$moduleInstance = CRMEntity::getInstance($module);
				$columnSQL = $selectedfields[0].$module.".".$selectedfields[1]."/".$moduleInstance->table_name.".conversion_rate AS '". decode_html($header_label) ."'";
				$this->queryPlanner->addTable($selectedfields[0].$module);
			} else {
				$columnSQL = $selectedfields[0].$module.".".$selectedfields[1]." AS '". decode_html($header_label) ."'";
				$this->queryPlanner->addTable($selectedfields[0].$module);
			}
		} else {
			$columnSQL = $selectedfields[0].".".$selectedfields[1]." AS '". decode_html($header_label) ."'";
            $this->queryPlanner->addTable($selectedfields[0]);
        }
        return $columnSQL;
	}


	/** Function to get field columns based on profile
	 *  @ param $module : Type string
	 *  returns permitted fields in array format
	 */
	function getaccesfield($module) {
		global $current_user;
		global $adb;
		$access_fields = Array();

		$profileList = getCurrentUserProfileList();
		$query = "select ncrm_field.fieldname from ncrm_field inner join ncrm_profile2field on ncrm_profile2field.fieldid=ncrm_field.fieldid inner join ncrm_def_org_field on ncrm_def_org_field.fieldid=ncrm_field.fieldid where";
		$params = array();
		if($module == "Calendar")
		{
			if (count($profileList) > 0) {
				$query .= " ncrm_field.tabid in (9,16) and ncrm_field.displaytype in (1,2,3) and ncrm_profile2field.visible=0 and ncrm_def_org_field.visible=0
								and ncrm_field.presence IN (0,2) and ncrm_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by ncrm_field.fieldid order by block,sequence";
				array_push($params, $profileList);
			} else {
				$query .= " ncrm_field.tabid in (9,16) and ncrm_field.displaytype in (1,2,3) and ncrm_profile2field.visible=0 and ncrm_def_org_field.visible=0
								and ncrm_field.presence IN (0,2) group by ncrm_field.fieldid order by block,sequence";
			}
		}
		else
		{
			array_push($params, $module);
			if (count($profileList) > 0) {
				$query .= " ncrm_field.tabid in (select tabid from ncrm_tab where ncrm_tab.name in (?)) and ncrm_field.displaytype in (1,2,3,5) and ncrm_profile2field.visible=0
								and ncrm_field.presence IN (0,2) and ncrm_def_org_field.visible=0 and ncrm_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by ncrm_field.fieldid order by block,sequence";
				array_push($params, $profileList);
			} else {
				$query .= " ncrm_field.tabid in (select tabid from ncrm_tab where ncrm_tab.name in (?)) and ncrm_field.displaytype in (1,2,3,5) and ncrm_profile2field.visible=0
								and ncrm_field.presence IN (0,2) and ncrm_def_org_field.visible=0 group by ncrm_field.fieldid order by block,sequence";
			}
		}
		$result = $adb->pquery($query, $params);

		while($collistrow = $adb->fetch_array($result))
		{
			$access_fields[] = $collistrow["fieldname"];
		}
		//added to include ticketid for Reports module in select columnlist for all users
		if($module == "HelpDesk")
			$access_fields[] = "ticketid";
		return $access_fields;
	}

	/** Function to get Escapedcolumns for the field in case of multiple parents
	 *  @ param $selectedfields : Type Array
	 *  returns the case query for the escaped columns
	 */
	function getEscapedColumns($selectedfields) {

		$tableName = $selectedfields[0];
		$columnName = $selectedfields[1];
		$moduleFieldLabel = $selectedfields[2];
		$fieldName = $selectedfields[3];
		list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
		$fieldInfo = getFieldByReportLabel($moduleName, $fieldLabel);

		if($moduleName == 'ModComments' && $fieldName == 'creator') {
			$concatSql = getSqlForNameInDisplayFormat(array('first_name' => 'ncrm_usersModComments.first_name',
															'last_name' => 'ncrm_usersModComments.last_name'), 'Users');
			$queryColumn = "trim(case when (ncrm_usersModComments.user_name not like '' and ncrm_crmentity.crmid!='') then $concatSql end) AS ModComments_Creator";
			$this->queryPlanner->addTable('ncrm_usersModComments');
            $this->queryPlanner->addTable("ncrm_usersModComments");
        } elseif(($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype']))
				&& $fieldInfo['uitype'] != '52' && $fieldInfo['uitype'] != '53') {
			$fieldSqlColumns = $this->getReferenceFieldColumnList($moduleName, $fieldInfo);
			if(count($fieldSqlColumns) > 0) {
				$queryColumn = "(CASE WHEN $tableName.$columnName NOT LIKE '' THEN (CASE";
				foreach($fieldSqlColumns as $columnSql) {
					$queryColumn .= " WHEN $columnSql NOT LIKE '' THEN $columnSql";
				}
				// Fix for http://code.ncrm.com/ncrm/ncrmcrm/issues/48
				$moduleFieldLabel = vtlib_purify(decode_html($moduleFieldLabel));
				$queryColumn .= " ELSE '' END) ELSE '' END) AS '$moduleFieldLabel'";
				$this->queryPlanner->addTable($tableName);
			}
		}
		return $queryColumn;
	}

	/** Function to get selectedcolumns for the given reportid
	 *  @ param $reportid : Type Integer
	 *  returns the query of columnlist for the selected columns
	 */
	function getSelectedColumnsList($reportid)
	{

		global $adb;
		global $modules;
		global $log;

		$ssql = "select ncrm_selectcolumn.* from ncrm_report inner join ncrm_selectquery on ncrm_selectquery.queryid = ncrm_report.queryid";
		$ssql .= " left join ncrm_selectcolumn on ncrm_selectcolumn.queryid = ncrm_selectquery.queryid where ncrm_report.reportid = ? ";
		$ssql .= " order by ncrm_selectcolumn.columnindex";

		$result = $adb->pquery($ssql, array($reportid));
		$noofrows = $adb->num_rows($result);

		if ($this->orderbylistsql != "")
		{
			$sSQL .= $this->orderbylistsql.", ";
		}

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$ordercolumnsequal = true;
			if($fieldcolname != "")
			{
				for($j=0;$j<count($this->orderbylistcolumns);$j++)
				{
					if($this->orderbylistcolumns[$j] == $fieldcolname)
					{
						$ordercolumnsequal = false;
						break;
					}else
					{
						$ordercolumnsequal = true;
					}
				}
				if($ordercolumnsequal)
				{
					$selectedfields = explode(":",$fieldcolname);
					if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule)
						$selectedfields[0] = "ncrm_crmentity";
					$sSQLList[] = $selectedfields[0].".".$selectedfields[1]." '".$selectedfields[2]."'";
				}
			}
		}
		$sSQL .= implode(",",$sSQLList);

		$log->info("ReportRun :: Successfully returned getSelectedColumnsList".$reportid);
		return $sSQL;
	}

	/** Function to get advanced comparator in query form for the given Comparator and value
	 *  @ param $comparator : Type String
	 *  @ param $value : Type String
	 *  returns the check query for the comparator
	 */
	function getAdvComparator($comparator,$value,$datatype="",$columnName='')
	{

		global $log,$adb,$default_charset,$ogReport;
		$value=html_entity_decode(trim($value),ENT_QUOTES,$default_charset);
		$value_len = strlen($value);
		$is_field = false;
		if($value_len > 1 && $value[0]=='$' && $value[$value_len-1]=='$'){
			$temp = str_replace('$','',$value);
			$is_field = true;
		}
		if($datatype=='C'){
			$value = str_replace("yes","1",str_replace("no","0",$value));
		}

		if($is_field==true){
			$value = $this->getFilterComparedField($temp);
		}
		if($comparator == "e")
		{
			if(trim($value) == "NULL")
			{
				$rtvalue = " is NULL";
			}elseif(trim($value) != "")
			{
				$rtvalue = " = ".$adb->quote($value);
			}elseif(trim($value) == "" && $datatype == "V")
			{
				$rtvalue = " = ".$adb->quote($value);
			}else
			{
				$rtvalue = " is NULL";
			}
		}
		if($comparator == "n")
		{
			if(trim($value) == "NULL")
			{
				$rtvalue = " is NOT NULL";
			}elseif(trim($value) != "")
			{
                if($columnName)
                    $rtvalue = " <> ".$adb->quote($value)." OR ".$columnName." IS NULL ";
                else
                    $rtvalue = " <> ".$adb->quote($value);
			}elseif(trim($value) == "" && $datatype == "V")
			{
				$rtvalue = " <> ".$adb->quote($value);
			}else
			{
				$rtvalue = " is NOT NULL";
			}
		}
		if($comparator == "s")
		{
			$rtvalue = " like '". formatForSqlLike($value, 2,$is_field) ."'";
		}
		if($comparator == "ew")
		{
			$rtvalue = " like '". formatForSqlLike($value, 1,$is_field) ."'";
		}
		if($comparator == "c")
		{
			$rtvalue = " like '". formatForSqlLike($value,0,$is_field) ."'";
		}
		if($comparator == "k")
		{
			$rtvalue = " not like '". formatForSqlLike($value,0,$is_field) ."'";
		}
		if($comparator == "l")
		{
			$rtvalue = " < ".$adb->quote($value);
		}
		if($comparator == "g")
		{
			$rtvalue = " > ".$adb->quote($value);
		}
		if($comparator == "m")
		{
			$rtvalue = " <= ".$adb->quote($value);
		}
		if($comparator == "h")
		{
			$rtvalue = " >= ".$adb->quote($value);
		}
		if($comparator == "b") {
			$rtvalue = " < ".$adb->quote($value);
		}
		if($comparator == "a") {
			$rtvalue = " > ".$adb->quote($value);
		}
		if($is_field==true){
			$rtvalue = str_replace("'","",$rtvalue);
			$rtvalue = str_replace("\\","",$rtvalue);
		}
		$log->info("ReportRun :: Successfully returned getAdvComparator");
		return $rtvalue;
	}

	/** Function to get field that is to be compared in query form for the given Comparator and field
	 *  @ param $field : field
	 *  returns the value for the comparator
	 */
	function getFilterComparedField($field){
		global $adb,$ogReport;
		if(!empty ($this->secondarymodule)){
		    $secModules = explode(':',$this->secondarymodule);
		    foreach ($secModules as $secModule){
			$secondary = CRMEntity::getInstance($secModule);
			$this->queryPlanner->addTable($secondary->table_name);
		    }
		}
			$field = split('#',$field);
			$module = $field[0];
			$fieldname = trim($field[1]);
			$tabid = getTabId($module);
			$field_query = $adb->pquery("SELECT tablename,columnname,typeofdata,fieldname,uitype FROM ncrm_field WHERE tabid = ? AND fieldname= ?",array($tabid,$fieldname));
			$fieldtablename = $adb->query_result($field_query,0,'tablename');
			$fieldcolname = $adb->query_result($field_query,0,'columnname');
			$typeofdata = $adb->query_result($field_query,0,'typeofdata');
			$fieldtypeofdata=ChangeTypeOfData_Filter($fieldtablename,$fieldcolname,$typeofdata[0]);
			$uitype = $adb->query_result($field_query,0,'uitype');
			/*if($tr[0]==$ogReport->primodule)
				$value = $adb->query_result($field_query,0,'tablename').".".$adb->query_result($field_query,0,'columnname');
			else
				$value = $adb->query_result($field_query,0,'tablename').$tr[0].".".$adb->query_result($field_query,0,'columnname');
			*/
			if($uitype == 68 || $uitype == 59)
			{
				$fieldtypeofdata = 'V';
			}
			if($fieldtablename == "ncrm_crmentity" && $module != $this->primarymodule)
			{
				$fieldtablename = $fieldtablename.$module;
			}
			if($fieldname == "assigned_user_id")
			{
				$fieldtablename = "ncrm_users".$module;
				$fieldcolname = "user_name";
			}
            if($fieldtablename == "ncrm_crmentity" && $fieldname == "modifiedby")
			{
				$fieldtablename = "ncrm_lastModifiedBy".$module;
				$fieldcolname = "user_name";
			}
			if($fieldname == "assigned_user_id1")
			{
				$fieldtablename = "ncrm_usersRel1";
				$fieldcolname = "user_name";
			}

			$value = $fieldtablename.".".$fieldcolname;

			$this->queryPlanner->addTable($fieldtablename);
		return $value;
	}
	/** Function to get the advanced filter columns for the reportid
	 *  This function accepts the $reportid
	 *  This function returns  $columnslist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
	 *					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
	 *					      					|
 	 *					      $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen filtercriteria
	 *				      	     )
	 *
	 */
	 function getAdvFilterList($reportid) {
		global $adb, $log;

		$advft_criteria = array();

		$sql = 'SELECT * FROM ncrm_relcriteria_grouping WHERE queryid = ? ORDER BY groupid';
		$groupsresult = $adb->pquery($sql, array($reportid));

		$i = 1;
		$j = 0;
		while($relcriteriagroup = $adb->fetch_array($groupsresult)) {
			$groupId = $relcriteriagroup["groupid"];
			$groupCondition = $relcriteriagroup["group_condition"];

			$ssql = 'select ncrm_relcriteria.* from ncrm_report
						inner join ncrm_relcriteria on ncrm_relcriteria.queryid = ncrm_report.queryid
						left join ncrm_relcriteria_grouping on ncrm_relcriteria.queryid = ncrm_relcriteria_grouping.queryid
								and ncrm_relcriteria.groupid = ncrm_relcriteria_grouping.groupid';
			$ssql.= " where ncrm_report.reportid = ? AND ncrm_relcriteria.groupid = ? order by ncrm_relcriteria.columnindex";

			$result = $adb->pquery($ssql, array($reportid, $groupId));
			$noOfColumns = $adb->num_rows($result);
			if($noOfColumns <= 0) continue;

			while($relcriteriarow = $adb->fetch_array($result)) {
				$columnIndex = $relcriteriarow["columnindex"];
				$criteria = array();
				$criteria['columnname'] = html_entity_decode($relcriteriarow["columnname"]);
				$criteria['comparator'] = $relcriteriarow["comparator"];
				$advfilterval = $relcriteriarow["value"];
				$col = explode(":",$relcriteriarow["columnname"]);
				$criteria['value'] = $advfilterval;
				$criteria['column_condition'] = $relcriteriarow["column_condition"];

				$advft_criteria[$i]['columns'][$j] = $criteria;
				$advft_criteria[$i]['condition'] = $groupCondition;
				$j++;

				$this->queryPlanner->addTable($col[0]);
			}
			if(!empty($advft_criteria[$i]['columns'][$j-1]['column_condition'])) {
				$advft_criteria[$i]['columns'][$j-1]['column_condition'] = '';
			}
			$i++;
		}
		// Clear the condition (and/or) for last group, if any.
		if(!empty($advft_criteria[$i-1]['condition'])) $advft_criteria[$i-1]['condition'] = '';
		return $advft_criteria;
	}

	function generateAdvFilterSql($advfilterlist) {

		global $adb;

		$advfiltersql = "";
        $customView = new CustomView();
		$dateSpecificConditions = $customView->getStdFilterConditions();

		foreach($advfilterlist as $groupindex => $groupinfo) {
			$groupcondition = $groupinfo['condition'];
			$groupcolumns = $groupinfo['columns'];

			if(count($groupcolumns) > 0) {

				$advfiltergroupsql = "";
				foreach($groupcolumns as $columnindex => $columninfo) {
					$fieldcolname = $columninfo["columnname"];
					$comparator = $columninfo["comparator"];
					$value = $columninfo["value"];
					$columncondition = $columninfo["column_condition"];
                    $advcolsql = array();

					if($fieldcolname != "" && $comparator != "") {
						if(in_array($comparator, $dateSpecificConditions)) {
							if($fieldcolname != 'none') {
								$selectedFields = explode(':',$fieldcolname);
								if($selectedFields[0] == 'ncrm_crmentity'.$this->primarymodule) {
									$selectedFields[0] = 'ncrm_crmentity';
								}

								if($comparator != 'custom') {
									list($startDate, $endDate) = $this->getStandarFiltersStartAndEndDate($comparator);
								} else {
                                    list($startDateTime, $endDateTime) = explode(',', $value);
									list($startDate, $startTime) = explode(' ', $startDateTime);
									list($endDate, $endTime) = explode(' ', $endDateTime);
                                }

								$type = $selectedFields[4];
								if($startDate != '0000-00-00' && $endDate != '0000-00-00' && $startDate != '' && $endDate != '') {
									$startDateTime = new DateTimeField($startDate. ' ' .date('H:i:s'));
									$userStartDate = $startDateTime->getDisplayDate();
									if($type == 'DT') {
										$userStartDate = $userStartDate.' 00:00:00';
									}
									$startDateTime = getValidDBInsertDateTimeValue($userStartDate);

									$endDateTime = new DateTimeField($endDate. ' ' .date('H:i:s'));
									$userEndDate = $endDateTime->getDisplayDate();
									if($type == 'DT') {
										$userEndDate = $userEndDate.' 23:59:59';
									}
									$endDateTime = getValidDBInsertDateTimeValue($userEndDate);

									if ($selectedFields[1] == 'birthday') {
										$tableColumnSql = 'DATE_FORMAT(' . $selectedFields[0] . '.' . $selectedFields[1] . ', "%m%d")';
										$startDateTime = "DATE_FORMAT('$startDateTime', '%m%d')";
										$endDateTime = "DATE_FORMAT('$endDateTime', '%m%d')";
									} else {
										if($selectedFields[0] == 'ncrm_activity' && ($selectedFields[1] == 'date_start')) {
											$tableColumnSql = 'CAST((CONCAT(date_start, " ", time_start)) AS DATETIME)';
										} else {
											$tableColumnSql = $selectedFields[0]. '.' .$selectedFields[1];
										}
										$startDateTime = "'$startDateTime'";
										$endDateTime = "'$endDateTime'";
									}

									$advfiltergroupsql .= "$tableColumnSql BETWEEN $startDateTime AND $endDateTime";
									if(!empty($columncondition)) {
										$advfiltergroupsql .= ' '.$columncondition.' ';
									}

									$this->queryPlanner->addTable($selectedFields[0]);
								}
							}
                            continue;
                        }
                        $selectedFields = explode(":",$fieldcolname);
                        $tempComparators = array('e','n','bw','a','b');
                        if($selectedFields[4] == 'DT' && in_array($comparator, $tempComparators)){
                            if($selectedFields[0] == 'ncrm_crmentity'.$this->primarymodule) {
                                $selectedFields[0] = 'ncrm_crmentity';
                            }

                            if($selectedFields[0] == 'ncrm_activity' && ($selectedFields[1] == 'date_start')) {
                                $tableColumnSql = 'CAST((CONCAT(date_start, " ", time_start)) AS DATETIME)';
                            } else {
                                $tableColumnSql = $selectedFields[0]. '.' .$selectedFields[1];
                            }

                            if($value != null && $value != ''){
                                if($comparator == 'e' || $comparator == 'n'){
                                    $dateTimeComponents = explode(' ', $value);
                                    $dateTime = new DateTime($dateTimeComponents[0].' '.'00:00:00');
                                    $date1 = $dateTime->format('Y-m-d H:i:s');
                                    $dateTime->modify("+1 days");
                                    $date2 = $dateTime->format('Y-m-d H:i:s');
                                    $tempDate = strtotime($date2)-1;
                                    $date2 = date('Y-m-d H:i:s', $tempDate);

                                    $start = getValidDBInsertDateTimeValue($date1);
                                    $end = getValidDBInsertDateTimeValue($date2);
                                    $start = "'$start'";
                                    $end = "'$end'";
                                    if($comparator == 'e')
                                        $advfiltergroupsql .= "$tableColumnSql BETWEEN $start AND $end";
                                    else
                                        $advfiltergroupsql .= "$tableColumnSql NOT BETWEEN $start AND $end";

                                }else if($comparator == 'bw'){
                                    $values = explode(',',$value);
                                    $startDateTime = explode(' ',$values[0]);
                                    $endDateTime = explode(' ',$values[1]);

                                    $startDateTime = new DateTimeField($startDateTime[0]. ' ' .date('H:i:s'));
                                    $userStartDate = $startDateTime->getDisplayDate();
                                    $userStartDate = $userStartDate.' 00:00:00';
                                    $start = getValidDBInsertDateTimeValue($userStartDate);

                                    $endDateTime = new DateTimeField($endDateTime[0]. ' ' .date('H:i:s'));
                                    $userEndDate = $endDateTime->getDisplayDate();
                                    $userEndDate = $userEndDate.' 23:59:59';
                                    $end = getValidDBInsertDateTimeValue($userEndDate);

                                    $advfiltergroupsql .= "$tableColumnSql BETWEEN '$start' AND '$end'";
                                }else if($comparator == 'a' || $comparator == 'b'){
                                    $value = explode(' ', $value);
                                    $dateTime = new DateTime($value[0]);
                                    if($comparator == 'a'){
                                        $modifiedDate = $dateTime->modify('+1 days');
                                        $nextday = $modifiedDate->format('Y-m-d H:i:s');
                                        $temp = strtotime($nextday)-1;
                                        $date = date('Y-m-d H:i:s', $temp);
                                        $value = getValidDBInsertDateTimeValue($date);
                                        $advfiltergroupsql .= "$tableColumnSql > '$value'";
                                    }else{
                                        $prevday = $dateTime->format('Y-m-d H:i:s');
                                        $temp = strtotime($prevday)-1;
                                        $date = date('Y-m-d H:i:s', $temp);
                                        $value = getValidDBInsertDateTimeValue($date);
                                        $advfiltergroupsql .= "$tableColumnSql < '$value'";
                                    }
                                }
                                if(!empty($columncondition)) {
                                            $advfiltergroupsql .= ' '.$columncondition.' ';
                                }
                                $this->queryPlanner->addTable($selectedFields[0]);
                            }
                            continue;
                        }
						$selectedfields = explode(":",$fieldcolname);
						$moduleFieldLabel = $selectedfields[2];
						list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
						$fieldInfo = getFieldByReportLabel($moduleName, $fieldLabel);
                        $concatSql = getSqlForNameInDisplayFormat(array('first_name'=>$selectedfields[0].".first_name",'last_name'=>$selectedfields[0].".last_name"), 'Users');
						// Added to handle the crmentity table name for Primary module
                        if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule) {
                            $selectedfields[0] = "ncrm_crmentity";
                        }
						//Added to handle yes or no for checkbox  field in reports advance filters. -shahul
						if($selectedfields[4] == 'C') {
							if(strcasecmp(trim($value),"yes")==0)
								$value="1";
							if(strcasecmp(trim($value),"no")==0)
								$value="0";
						}
                        if(in_array($comparator,$dateSpecificConditions)) {
                            $customView = new CustomView($moduleName);
                            $columninfo['stdfilter'] = $columninfo['comparator'];
                            $valueComponents = explode(',',$columninfo['value']);
                            if($comparator == 'custom') {
								if($selectedfields[4] == 'DT') {
									$startDateTimeComponents = explode(' ',$valueComponents[0]);
									$endDateTimeComponents = explode(' ',$valueComponents[1]);
									$columninfo['startdate'] = DateTimeField::convertToDBFormat($startDateTimeComponents[0]);
									$columninfo['enddate'] = DateTimeField::convertToDBFormat($endDateTimeComponents[0]);
								} else {
									$columninfo['startdate'] = DateTimeField::convertToDBFormat($valueComponents[0]);
									$columninfo['enddate'] = DateTimeField::convertToDBFormat($valueComponents[1]);
								}
                            }
                            $dateFilterResolvedList = $customView->resolveDateFilterValue($columninfo);
                            $startDate = DateTimeField::convertToDBFormat($dateFilterResolvedList['startdate']);
                            $endDate = DateTimeField::convertToDBFormat($dateFilterResolvedList['enddate']);
                            $columninfo['value'] = $value  = implode(',', array($startDate,$endDate));
                            $comparator = 'bw';
                        }
						$valuearray = explode(",",trim($value));
						$datatype = (isset($selectedfields[4])) ? $selectedfields[4] : "";
						if(isset($valuearray) && count($valuearray) > 1 && $comparator != 'bw') {

							$advcolumnsql = "";
							for($n=0;$n<count($valuearray);$n++) {

		                		if(($selectedfields[0] == "ncrm_users".$this->primarymodule || $selectedfields[0] == "ncrm_users".$this->secondarymodule) && $selectedfields[1] == 'user_name') {
									$module_from_tablename = str_replace("ncrm_users","",$selectedfields[0]);
									$advcolsql[] = " (trim($concatSql)".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype)." or ncrm_groups".$module_from_tablename.".groupname ".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype).")";
									$this->queryPlanner->addTable("ncrm_groups".$module_from_tablename);
								} elseif($selectedfields[1] == 'status') {//when you use comma seperated values.
									if($selectedfields[2] == 'Calendar_Status') {
										$advcolsql[] = "(case when (ncrm_activity.status not like '') then ncrm_activity.status else ncrm_activity.eventstatus end)".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
									} else if($selectedfields[2] == 'HelpDesk_Status') {
										$advcolsql[] = "ncrm_troubletickets.status".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
									} else if($selectedfields[2] == 'Faq_Status') {
										$advcolsql[] = "ncrm_faq.status".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
									}
                                    else
                                    $advcolsql[] = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
								} elseif($selectedfields[1] == 'description') {//when you use comma seperated values.
									if($selectedfields[0]=='ncrm_crmentity'.$this->primarymodule)
										$advcolsql[] = "ncrm_crmentity.description".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
									else
										$advcolsql[] = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
								} elseif($selectedfields[2] == 'Quotes_Inventory_Manager'){
									$advcolsql[] = ("trim($concatSql)".$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype));
								} elseif($selectedfields[1] == 'modifiedby'){
                                    $module_from_tablename = str_replace("ncrm_crmentity","",$selectedfields[0]);
                                    if($module_from_tablename != '') {
                                        $tableName = 'ncrm_lastModifiedBy'.$module_from_tablename;
								} else {
                                        $tableName = 'ncrm_lastModifiedBy'.$this->primarymodule;
                                    }
                                    $advcolsql[] = 'trim('.getSqlForNameInDisplayFormat(array('last_name'=>"$tableName.last_name",'first_name'=>"$tableName.first_name"), 'Users').')'.
                                                    $this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
                                }
                                else {
									$advcolsql[] = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($valuearray[$n]),$datatype);
								}
							}
							//If negative logic filter ('not equal to', 'does not contain') is used, 'and' condition should be applied instead of 'or'
							if($comparator == 'n' || $comparator == 'k')
								$advcolumnsql = implode(" and ",$advcolsql);
							else
								$advcolumnsql = implode(" or ",$advcolsql);
							$fieldvalue = " (".$advcolumnsql.") ";
						} elseif($selectedfields[1] == 'user_name') {
							if($selectedfields[0] == "ncrm_users".$this->primarymodule) {
								$module_from_tablename = str_replace("ncrm_users","",$selectedfields[0]);
								$fieldvalue = " trim(case when (".$selectedfields[0].".last_name NOT LIKE '') then ".$concatSql." else ncrm_groups".$module_from_tablename.".groupname end) ".$this->getAdvComparator($comparator,trim($value),$datatype);
								$this->queryPlanner->addTable("ncrm_groups".$module_from_tablename);
							} else {
								$secondaryModules = explode(':', $this->secondarymodule);
								$firstSecondaryModule = "ncrm_users".$secondaryModules[0];
								$secondSecondaryModule = "ncrm_users".$secondaryModules[1];
 								if(($firstSecondaryModule && $firstSecondaryModule == $selectedfields[0]) || ($secondSecondaryModule && $secondSecondaryModule == $selectedfields[0])) {
									$module_from_tablename = str_replace("ncrm_users","",$selectedfields[0]);
									$moduleInstance = CRMEntity::getInstance($module_from_tablename);
									$fieldvalue = " trim(case when (".$selectedfields[0].".last_name NOT LIKE '') then ".$concatSql." else ncrm_groups".$module_from_tablename.".groupname end) ".$this->getAdvComparator($comparator,trim($value),$datatype);
									$this->queryPlanner->addTable("ncrm_groups".$module_from_tablename);
									$this->queryPlanner->addTable($moduleInstance->table_name);
								}
							}
						} elseif($comparator == 'bw' && count($valuearray) == 2) {
							if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule) {
								$fieldvalue = "("."ncrm_crmentity.".$selectedfields[1]." between '".trim($valuearray[0])."' and '".trim($valuearray[1])."')";
							} else {
								$fieldvalue = "(".$selectedfields[0].".".$selectedfields[1]." between '".trim($valuearray[0])."' and '".trim($valuearray[1])."')";
							}
						} elseif($selectedfields[0] == "ncrm_crmentity".$this->primarymodule) {
							$fieldvalue = "ncrm_crmentity.".$selectedfields[1]." ".$this->getAdvComparator($comparator,trim($value),$datatype);
						} elseif($selectedfields[2] == 'Quotes_Inventory_Manager'){
							$fieldvalue = ("trim($concatSql)" . $this->getAdvComparator($comparator,trim($value),$datatype));
						} elseif($selectedfields[1]=='modifiedby') {
                            $module_from_tablename = str_replace("ncrm_crmentity","",$selectedfields[0]);
                            if($module_from_tablename != '') {
								$tableName = 'ncrm_lastModifiedBy'.$module_from_tablename;
							} else {
								$tableName = 'ncrm_lastModifiedBy'.$this->primarymodule;
							}
							$this->queryPlanner->addTable($tableName);
							$fieldvalue = 'trim('.getSqlForNameInDisplayFormat(array('last_name'=>"$tableName.last_name",'first_name'=>"$tableName.first_name"), 'Users').')'.
									$this->getAdvComparator($comparator,trim($value),$datatype);
						} elseif($selectedfields[1]=='smcreatorid'){
                            $module_from_tablename = str_replace("ncrm_crmentity","",$selectedfields[0]);
                            if($module_from_tablename != '') {
								$tableName = 'ncrm_createdby'.$module_from_tablename;
                            } else {
                                $tableName = 'ncrm_createdby'.$this->primarymodule;
                            }
                            if($moduleName == 'ModComments') {
                                $tableName = 'ncrm_users'.$moduleName;
                            }
                            $this->queryPlanner->addTable($tableName);
                            $fieldvalue = 'trim('.getSqlForNameInDisplayFormat(array('last_name'=>"$tableName.last_name",'first_name'=>"$tableName.first_name"), 'Users').')'.
									$this->getAdvComparator($comparator,trim($value),$datatype);
						} elseif($selectedfields[0] == "ncrm_activity" && ($selectedfields[1] == 'status' || $selectedfields[1] == 'eventstatus')) {
                            // for "Is Empty" condition we need to check with "value NOT NULL" OR "value = ''" conditions
                            if($comparator == 'y'){
                                $fieldvalue = "(case when (ncrm_activity.status not like '') then ncrm_activity.status
                                                else ncrm_activity.eventstatus end) IS NULL OR (case when (ncrm_activity.status not like '')
                                                then ncrm_activity.status else ncrm_activity.eventstatus end) = ''";
                            }else{
                                $fieldvalue = "(case when (ncrm_activity.status not like '') then ncrm_activity.status
                                                else ncrm_activity.eventstatus end)".$this->getAdvComparator($comparator,trim($value),$datatype);
                            }
						}else if($comparator == 'ny'){
                            if($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype']))
                                $fieldvalue = "(".$selectedfields[0].".".$selectedfields[1]." IS NOT NULL AND ".$selectedfields[0].".".$selectedfields[1]." != '' AND ".$selectedfields[0].".".$selectedfields[1]."  != '0')";
                            else
                                $fieldvalue = "(".$selectedfields[0].".".$selectedfields[1]." IS NOT NULL AND ".$selectedfields[0].".".$selectedfields[1]." != '')";
                        }elseif($comparator == 'y' || ($comparator == 'e' && (trim($value) == "NULL" || trim($value) == ''))) {
							if($selectedfields[0] == 'ncrm_inventoryproductrel') {
								$selectedfields[0]='ncrm_inventoryproductrel'.$moduleName;
							}
                            if($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype']))
                                $fieldvalue = "(".$selectedfields[0].".".$selectedfields[1]." IS NULL OR ".$selectedfields[0].".".$selectedfields[1]." = '' OR ".$selectedfields[0].".".$selectedfields[1]." = '0')";
                            else
                                $fieldvalue = "(".$selectedfields[0].".".$selectedfields[1]." IS NULL OR ".$selectedfields[0].".".$selectedfields[1]." = '')";
						} elseif($selectedfields[0] == 'ncrm_inventoryproductrel' ) {
							if($selectedfields[1] == 'productid'){
									$fieldvalue = "ncrm_products$moduleName.productname ".$this->getAdvComparator($comparator,trim($value),$datatype);
									$this->queryPlanner->addTable("ncrm_products$moduleName");
							} else if($selectedfields[1] == 'serviceid'){
								$fieldvalue = "ncrm_service$moduleName.servicename ".$this->getAdvComparator($comparator,trim($value),$datatype);
								$this->queryPlanner->addTable("ncrm_service$moduleName");
							}
							else{
							   //for inventory module table should be follwed by the module name
								$selectedfields[0]='ncrm_inventoryproductrel'.$moduleName;
								$fieldvalue = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator, $value, $datatype);
							}
						} elseif($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype'])) {

							$fieldSqlColumns = $this->getReferenceFieldColumnList($moduleName, $fieldInfo);
							$comparatorValue = $this->getAdvComparator($comparator,trim($value),$datatype,$fieldSqlColumns[0]);
							$fieldSqls = array();

							foreach($fieldSqlColumns as $columnSql) {
							 	$fieldSqls[] = $columnSql.$comparatorValue;
							}
							$fieldvalue = ' ('. implode(' OR ', $fieldSqls).') ';
						} else {
							$fieldvalue = $selectedfields[0].".".$selectedfields[1].$this->getAdvComparator($comparator,trim($value),$datatype);
						}

						$advfiltergroupsql .= $fieldvalue;
						if(!empty($columncondition)) {
							$advfiltergroupsql .= ' '.$columncondition.' ';
						}

						$this->queryPlanner->addTable($selectedfields[0]);
					}

				}

				if (trim($advfiltergroupsql) != "") {
					$advfiltergroupsql =  "( $advfiltergroupsql ) ";
					if(!empty($groupcondition)) {
						$advfiltergroupsql .= ' '. $groupcondition . ' ';
					}

					$advfiltersql .= $advfiltergroupsql;
				}
			}
		}
		if (trim($advfiltersql) != "") $advfiltersql = '('.$advfiltersql.')';

		return $advfiltersql;
	}

	function getAdvFilterSql($reportid) {
		// Have we initialized information already?
		if($this->_advfiltersql !== false) {
			return $this->_advfiltersql;
		}
		global $log;

		$advfilterlist = $this->getAdvFilterList($reportid);
		$advfiltersql = $this->generateAdvFilterSql($advfilterlist);

		// Save the information
		$this->_advfiltersql = $advfiltersql;

		$log->info("ReportRun :: Successfully returned getAdvFilterSql".$reportid);
		return $advfiltersql;
	}

	/** Function to get the Standard filter columns for the reportid
	 *  This function accepts the $reportid datatype Integer
	 *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
	 *					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
	 *				      	     )
	 *
	 */
	function getStdFilterList($reportid)
	{
		// Have we initialized information already?
		if($this->_stdfilterlist !== false) {
			return $this->_stdfilterlist;
		}

		global $adb, $log;
		$stdfilterlist = array();

		$stdfiltersql = "select ncrm_reportdatefilter.* from ncrm_report";
		$stdfiltersql .= " inner join ncrm_reportdatefilter on ncrm_report.reportid = ncrm_reportdatefilter.datefilterid";
		$stdfiltersql .= " where ncrm_report.reportid = ?";

		$result = $adb->pquery($stdfiltersql, array($reportid));
		$stdfilterrow = $adb->fetch_array($result);
		if(isset($stdfilterrow)) {
			$fieldcolname = $stdfilterrow["datecolumnname"];
			$datefilter = $stdfilterrow["datefilter"];
			$startdate = $stdfilterrow["startdate"];
			$enddate = $stdfilterrow["enddate"];

			if($fieldcolname != "none") {
				$selectedfields = explode(":",$fieldcolname);
				if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule)
					$selectedfields[0] = "ncrm_crmentity";

				$moduleFieldLabel = $selectedfields[3];
				list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
				$fieldInfo = getFieldByReportLabel($moduleName, $fieldLabel);
				$typeOfData = $fieldInfo['typeofdata'];
				list($type, $typeOtherInfo) = explode('~', $typeOfData, 2);

				if($datefilter != "custom") {
					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
					$startdate = $startenddate[0];
					$enddate = $startenddate[1];
				}

				if($startdate != "0000-00-00" && $enddate != "0000-00-00" && $startdate != "" && $enddate != ""
						&& $selectedfields[0] != "" && $selectedfields[1] != "") {

					$startDateTime = new DateTimeField($startdate.' '. date('H:i:s'));
					$userStartDate = $startDateTime->getDisplayDate();
					if($type == 'DT') {
						$userStartDate = $userStartDate.' 00:00:00';
					}
					$startDateTime = getValidDBInsertDateTimeValue($userStartDate);

					$endDateTime = new DateTimeField($enddate.' '. date('H:i:s'));
					$userEndDate = $endDateTime->getDisplayDate();
					if($type == 'DT') {
						$userEndDate = $userEndDate.' 23:59:00';
					}
					$endDateTime = getValidDBInsertDateTimeValue($userEndDate);

					if ($selectedfields[1] == 'birthday') {
						$tableColumnSql = "DATE_FORMAT(".$selectedfields[0].".".$selectedfields[1].", '%m%d')";
						$startDateTime = "DATE_FORMAT('$startDateTime', '%m%d')";
						$endDateTime = "DATE_FORMAT('$endDateTime', '%m%d')";
					} else {
						if($selectedfields[0] == 'ncrm_activity' && ($selectedfields[1] == 'date_start')) {
							$tableColumnSql = '';
							$tableColumnSql = "CAST((CONCAT(date_start,' ',time_start)) AS DATETIME)";
						} else {
							$tableColumnSql = $selectedfields[0].".".$selectedfields[1];
						}
						$startDateTime = "'$startDateTime'";
						$endDateTime = "'$endDateTime'";
					}

					$stdfilterlist[$fieldcolname] = $tableColumnSql." between ".$startDateTime." and ".$endDateTime;
					$this->queryPlanner->addTable($selectedfields[0]);
				}
			}
		}
		// Save the information
		$this->_stdfilterlist = $stdfilterlist;

		$log->info("ReportRun :: Successfully returned getStdFilterList".$reportid);
		return $stdfilterlist;
	}

	/** Function to get the RunTime filter columns for the given $filtercolumn,$filter,$startdate,$enddate
	 *  @ param $filtercolumn : Type String
	 *  @ param $filter : Type String
	 *  @ param $startdate: Type String
	 *  @ param $enddate : Type String
	 *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel=>$tablename.$columnname 'between' $startdate 'and' $enddate)
	 *
	 */
	function RunTimeFilter($filtercolumn,$filter,$startdate,$enddate)
	{
		if($filtercolumn != "none")
		{
			$selectedfields = explode(":",$filtercolumn);
			if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule)
				$selectedfields[0] = "ncrm_crmentity";
			if($filter == "custom")
			{
				if($startdate != "0000-00-00" && $enddate != "0000-00-00" && $startdate != "" &&
						$enddate != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
					$stdfilterlist[$filtercolumn] = $selectedfields[0].".".$selectedfields[1]." between '".$startdate." 00:00:00' and '".$enddate." 23:59:00'";
				}
			}else
			{
				if($startdate != "" && $enddate != "")
				{
					$startenddate = $this->getStandarFiltersStartAndEndDate($filter);
					if($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "")
					{
						$stdfilterlist[$filtercolumn] = $selectedfields[0].".".$selectedfields[1]." between '".$startenddate[0]." 00:00:00' and '".$startenddate[1]." 23:59:00'";
					}
				}
			}

		}
		return $stdfilterlist;

	}

	/** Function to get the RunTime Advanced filter conditions
	 *  @ param $advft_criteria : Type Array
	 *  @ param $advft_criteria_groups : Type Array
	 *  This function returns  $advfiltersql
	 *
	 */
	function RunTimeAdvFilter($advft_criteria,$advft_criteria_groups) {
		$adb = PearDatabase::getInstance();

		$advfilterlist = array();
        $advfiltersql = '';
		if(!empty($advft_criteria)) {
			foreach($advft_criteria as $column_index => $column_condition) {

				if(empty($column_condition)) continue;

				$adv_filter_column = $column_condition["columnname"];
				$adv_filter_comparator = $column_condition["comparator"];
				$adv_filter_value = $column_condition["value"];
				$adv_filter_column_condition = $column_condition["columncondition"];
				$adv_filter_groupid = $column_condition["groupid"];

				$column_info = explode(":",$adv_filter_column);

				$moduleFieldLabel = $column_info[2];
				$fieldName = $column_info[3];
				list($module, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
				$fieldInfo = getFieldByReportLabel($module, $fieldLabel);
				$fieldType = null;
				if(!empty($fieldInfo)) {
					$field = WebserviceField::fromArray($adb, $fieldInfo);
					$fieldType = $field->getFieldDataType();
				}

				if($fieldType == 'currency') {
					// Some of the currency fields like Unit Price, Total, Sub-total etc of Inventory modules, do not need currency conversion
					if($field->getUIType() == '72') {
						$adv_filter_value = CurrencyField::convertToDBFormat($adv_filter_value, null, true);
					} else {
						$adv_filter_value = CurrencyField::convertToDBFormat($adv_filter_value);
					}
				}

                $temp_val = explode(",",$adv_filter_value);
                if(($column_info[4] == 'D' || ($column_info[4] == 'T' && $column_info[1] != 'time_start' && $column_info[1] != 'time_end')
                        || ($column_info[4] == 'DT'))
                    && ($column_info[4] != '' && $adv_filter_value != '' )) {
                    $val = Array();
                    for($x=0;$x<count($temp_val);$x++) {
                        if($column_info[4] == 'D') {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDBInsertDateValue();
						} elseif($column_info[4] == 'DT') {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDBInsertDateTimeValue();
						} else {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDBInsertTimeValue();
						}
                    }
                    $adv_filter_value = implode(",",$val);
                }
				$criteria = array();
				$criteria['columnname'] = $adv_filter_column;
				$criteria['comparator'] = $adv_filter_comparator;
				$criteria['value'] = $adv_filter_value;
				$criteria['column_condition'] = $adv_filter_column_condition;

				$advfilterlist[$adv_filter_groupid]['columns'][] = $criteria;
			}

			foreach($advft_criteria_groups as $group_index => $group_condition_info) {
				if(empty($group_condition_info)) continue;
				if(empty($advfilterlist[$group_index])) continue;
				$advfilterlist[$group_index]['condition'] = $group_condition_info["groupcondition"];
				$noOfGroupColumns = count($advfilterlist[$group_index]['columns']);
				if(!empty($advfilterlist[$group_index]['columns'][$noOfGroupColumns-1]['column_condition'])) {
					$advfilterlist[$group_index]['columns'][$noOfGroupColumns-1]['column_condition'] = '';
				}
			}
			$noOfGroups = count($advfilterlist);
			if(!empty($advfilterlist[$noOfGroups]['condition'])) {
				$advfilterlist[$noOfGroups]['condition'] = '';
			}

			$advfiltersql = $this->generateAdvFilterSql($advfilterlist);
		}
		return $advfiltersql;

	}

	/** Function to get standardfilter for the given reportid
	 *  @ param $reportid : Type Integer
	 *  returns the query of columnlist for the selected columns
	 */

	function getStandardCriterialSql($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$sreportstdfiltersql = "select ncrm_reportdatefilter.* from ncrm_report";
		$sreportstdfiltersql .= " inner join ncrm_reportdatefilter on ncrm_report.reportid = ncrm_reportdatefilter.datefilterid";
		$sreportstdfiltersql .= " where ncrm_report.reportid = ?";

		$result = $adb->pquery($sreportstdfiltersql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++) {
			$fieldcolname = $adb->query_result($result,$i,"datecolumnname");
			$datefilter = $adb->query_result($result,$i,"datefilter");
			$startdate = $adb->query_result($result,$i,"startdate");
			$enddate = $adb->query_result($result,$i,"enddate");

			if($fieldcolname != "none") {
				$selectedfields = explode(":",$fieldcolname);
				if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule)
					$selectedfields[0] = "ncrm_crmentity";
				if($datefilter == "custom") {

					if($startdate != "0000-00-00" && $enddate != "0000-00-00" && $selectedfields[0] != "" && $selectedfields[1] != ""
							&& $startdate != '' && $enddate != '') {

						$startDateTime = new DateTimeField($startdate.' '. date('H:i:s'));
						$startdate = $startDateTime->getDisplayDate();
						$endDateTime = new DateTimeField($enddate.' '. date('H:i:s'));
						$enddate = $endDateTime->getDisplayDate();

						$sSQL .= $selectedfields[0].".".$selectedfields[1]." between '".$startdate."' and '".$enddate."'";
					}
				} else {

					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);

					$startDateTime = new DateTimeField($startenddate[0].' '. date('H:i:s'));
					$startdate = $startDateTime->getDisplayDate();
					$endDateTime = new DateTimeField($startenddate[1].' '. date('H:i:s'));
					$enddate = $endDateTime->getDisplayDate();

					if($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
						$sSQL .= $selectedfields[0].".".$selectedfields[1]." between '".$startdate."' and '".$enddate."'";
					}
				}
			}
		}
		$log->info("ReportRun :: Successfully returned getStandardCriterialSql".$reportid);
		return $sSQL;
	}

	/** Function to get standardfilter startdate and enddate for the given type
	 *  @ param $type : Type String
	 *  returns the $datevalue Array in the given format
	 * 		$datevalue = Array(0=>$startdate,1=>$enddate)
	 */


		function getStandarFiltersStartAndEndDate($type)
	{
        global $current_user;
        $userPeferredDayOfTheWeek = $current_user->column_fields['dayoftheweek'];

		$today = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $todayName =  date('l', strtotime( $today));

		$tomorrow  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
		$yesterday  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));

		$currentmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m"), "01",   date("Y")));
		$currentmonth1 = date("Y-m-t");
		$lastmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m")-1, "01",   date("Y")));
		$lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
		$nextmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m")+1, "01",   date("Y")));
		$nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

          // (Last Week) If Today is "Sunday" then "-2 week Sunday" will give before last week Sunday date
        if($todayName == $userPeferredDayOfTheWeek)
            $lastweek0 = date("Y-m-d",strtotime("-1 week $userPeferredDayOfTheWeek"));
        else
            $lastweek0 = date("Y-m-d", strtotime("-2 week $userPeferredDayOfTheWeek"));
        $prvDay = date('l',  strtotime(date('Y-m-d', strtotime('-1 day', strtotime($lastweek0)))));
        $lastweek1 = date("Y-m-d", strtotime("-1 week $prvDay"));

        // (This Week) If Today is "Sunday" then "-1 week Sunday" will give last week Sunday date
        if($todayName == $userPeferredDayOfTheWeek)
            $thisweek0 = date("Y-m-d",strtotime("-0 week $userPeferredDayOfTheWeek"));
        else
            $thisweek0 = date("Y-m-d", strtotime("-1 week $userPeferredDayOfTheWeek"));
        $prvDay = date('l',  strtotime(date('Y-m-d', strtotime('-1 day', strtotime($thisweek0)))));
		$thisweek1 = date("Y-m-d", strtotime("this $prvDay"));

         // (Next Week) If Today is "Sunday" then "this Sunday" will give Today's date
		if($todayName == $userPeferredDayOfTheWeek)
            $nextweek0 = date("Y-m-d",strtotime("+1 week $userPeferredDayOfTheWeek"));
        else
            $nextweek0 = date("Y-m-d", strtotime("this $userPeferredDayOfTheWeek"));
        $prvDay = date('l',  strtotime(date('Y-m-d', strtotime('-1 day', strtotime($nextweek0)))));
		$nextweek1 = date("Y-m-d", strtotime("+1 week $prvDay"));

		$next7days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+6, date("Y")));
		$next30days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+29, date("Y")));
		$next60days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+59, date("Y")));
		$next90days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+89, date("Y")));
		$next120days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+119, date("Y")));

		$last7days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
		$last30days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-29, date("Y")));
		$last60days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-59, date("Y")));
		$last90days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-89, date("Y")));
		$last120days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-119, date("Y")));

		$currentFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")));
		$currentFY1 = date("Y-m-t",mktime(0, 0, 0, "12", date("d"),   date("Y")));
		$lastFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")-1));
		$lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")-1));
		$nextFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")+1));
		$nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")+1));

		if(date("m") <= 3)
		{
			$cFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")));
			$nFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")-1));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")-1));
		}else if(date("m") > 3 and date("m") <= 6)
		{
			$pFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")));
			$cFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$nFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));

		}else if(date("m") > 6 and date("m") <= 9)
		{
			$nFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$cFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
		}
		else if(date("m") > 9 and date("m") <= 12)
		{
			$nFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")+1));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")+1));
			$pFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
			$cFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));

		}

		if($type == "today" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $today;
		}
		elseif($type == "yesterday" )
		{

			$datevalue[0] = $yesterday;
			$datevalue[1] = $yesterday;
		}
		elseif($type == "tomorrow" )
		{

			$datevalue[0] = $tomorrow;
			$datevalue[1] = $tomorrow;
		}
		elseif($type == "thisweek" )
		{

			$datevalue[0] = $thisweek0;
			$datevalue[1] = $thisweek1;
		}
		elseif($type == "lastweek" )
		{

			$datevalue[0] = $lastweek0;
			$datevalue[1] = $lastweek1;
		}
		elseif($type == "nextweek" )
		{

			$datevalue[0] = $nextweek0;
			$datevalue[1] = $nextweek1;
		}
		elseif($type == "thismonth" )
		{

			$datevalue[0] =$currentmonth0;
			$datevalue[1] = $currentmonth1;
		}

		elseif($type == "lastmonth" )
		{

			$datevalue[0] = $lastmonth0;
			$datevalue[1] = $lastmonth1;
		}
		elseif($type == "nextmonth" )
		{

			$datevalue[0] = $nextmonth0;
			$datevalue[1] = $nextmonth1;
		}
		elseif($type == "next7days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next7days;
		}
		elseif($type == "next30days" )
		{

			$datevalue[0] =$today;
			$datevalue[1] =$next30days;
		}
		elseif($type == "next60days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next60days;
		}
		elseif($type == "next90days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next90days;
		}
		elseif($type == "next120days" )
		{

			$datevalue[0] = $today;
			$datevalue[1] = $next120days;
		}
		elseif($type == "last7days" )
		{

			$datevalue[0] = $last7days;
			$datevalue[1] = $today;
		}
		elseif($type == "last30days" )
		{

			$datevalue[0] = $last30days;
			$datevalue[1] =  $today;
		}
		elseif($type == "last60days" )
		{

			$datevalue[0] = $last60days;
			$datevalue[1] = $today;
		}
		else if($type == "last90days" )
		{

			$datevalue[0] = $last90days;
			$datevalue[1] = $today;
		}
		elseif($type == "last120days" )
		{

			$datevalue[0] = $last120days;
			$datevalue[1] = $today;
		}
		elseif($type == "thisfy" )
		{

			$datevalue[0] = $currentFY0;
			$datevalue[1] = $currentFY1;
		}
		elseif($type == "prevfy" )
		{

			$datevalue[0] = $lastFY0;
			$datevalue[1] = $lastFY1;
		}
		elseif($type == "nextfy" )
		{

			$datevalue[0] = $nextFY0;
			$datevalue[1] = $nextFY1;
		}
		elseif($type == "nextfq" )
		{

			$datevalue[0] = $nFq;
			$datevalue[1] = $nFq1;
		}
		elseif($type == "prevfq" )
		{

			$datevalue[0] = $pFq;
			$datevalue[1] = $pFq1;
		}
		elseif($type == "thisfq" )
		{
			$datevalue[0] = $cFq;
			$datevalue[1] = $cFq1;
		}
		else
		{
			$datevalue[0] = "";
			$datevalue[1] = "";
		}
		return $datevalue;
	}

	function hasGroupingList() {
	    global $adb;
	    $result = $adb->pquery('SELECT 1 FROM ncrm_reportsortcol WHERE reportid=? and columnname <> "none"', array($this->reportid));
	    return ($result && $adb->num_rows($result))? true : false;
	}

	/** Function to get getGroupingList for the given reportid
	 *  @ param $reportid : Type Integer
	 *  returns the $grouplist Array in the following format
	 *  		$grouplist = Array($tablename:$columnname:$fieldlabel:fieldname:typeofdata=>$tablename:$columnname $sorder,
	 *				   $tablename1:$columnname1:$fieldlabel1:fieldname1:typeofdata1=>$tablename1:$columnname1 $sorder,
	 *				   $tablename2:$columnname2:$fieldlabel2:fieldname2:typeofdata2=>$tablename2:$columnname2 $sorder)
	 * This function also sets the return value in the class variable $this->groupbylist
	 */


	function getGroupingList($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		// Have we initialized information already?
		if($this->_groupinglist !== false) {
			return $this->_groupinglist;
		}

		$sreportsortsql = " SELECT ncrm_reportsortcol.*, ncrm_reportgroupbycolumn.* FROM ncrm_report";
		$sreportsortsql .= " inner join ncrm_reportsortcol on ncrm_report.reportid = ncrm_reportsortcol.reportid";
        $sreportsortsql .= " LEFT JOIN ncrm_reportgroupbycolumn ON (ncrm_report.reportid = ncrm_reportgroupbycolumn.reportid AND ncrm_reportsortcol.sortcolid = ncrm_reportgroupbycolumn.sortid)";
		$sreportsortsql .= " where ncrm_report.reportid =? AND ncrm_reportsortcol.columnname IN (SELECT columnname from ncrm_selectcolumn WHERE queryid=?) order by ncrm_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid,$reportid));
		$grouplist = array();

		$inventoryModules = getInventoryModules();
		while($reportsortrow = $adb->fetch_array($result))
		{
			$fieldcolname = $reportsortrow["columnname"];
			list($tablename,$colname,$module_field,$fieldname,$single) = split(":",$fieldcolname);
			$sortorder = $reportsortrow["sortorder"];

			if($sortorder == "Ascending")
			{
				$sortorder = "ASC";

			}elseif($sortorder == "Descending")
			{
				$sortorder = "DESC";
			}

			if($fieldcolname != "none")
			{
				$selectedfields = explode(":",$fieldcolname);
				if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule)
					$selectedfields[0] = "ncrm_crmentity";
				if(stripos($selectedfields[1],'cf_')==0 && stristr($selectedfields[1],'cf_')==true){
					//In sql queries forward slash(/) is treated as query terminator,so to avoid this problem
					//the column names are enclosed within ('[]'),which will treat this as part of column name
					$sqlvalue = "`".$adb->sql_escape_string(decode_html($selectedfields[2]))."` ".$sortorder;
				} else {
					$sqlvalue = "`".self::replaceSpecialChar($selectedfields[2])."` ".$sortorder;
				}
				if($selectedfields[4]=="D" && strtolower($reportsortrow["dategroupbycriteria"])!="none"){
					$groupField = $module_field;
					$groupCriteria = $reportsortrow["dategroupbycriteria"];
					if(in_array($groupCriteria,array_keys($this->groupByTimeParent))){
						$parentCriteria = $this->groupByTimeParent[$groupCriteria];
						foreach($parentCriteria as $criteria){
						  $groupByCondition[]=$this->GetTimeCriteriaCondition($criteria, $groupField)." ".$sortorder;
						}
					}
					$groupByCondition[] =$this->GetTimeCriteriaCondition($groupCriteria, $groupField)." ".$sortorder;
					$sqlvalue = implode(", ",$groupByCondition);
				}
				$grouplist[$fieldcolname] = $sqlvalue;
				$temp = split("_",$selectedfields[2],2);
				$module = $temp[0];
				if (in_array($module, $inventoryModules) && $fieldname == 'serviceid') {
					$grouplist[$fieldcolname] = $sqlvalue;
				} else if(CheckFieldPermission($fieldname,$module) == 'true') {
					$grouplist[$fieldcolname] = $sqlvalue;
				} else {
					$grouplist[$fieldcolname] = $selectedfields[0].".".$selectedfields[1];
				}

				$this->queryPlanner->addTable($tablename);
			}
		}

		// Save the information
		$this->_groupinglist = $grouplist;

		$log->info("ReportRun :: Successfully returned getGroupingList".$reportid);
		return $grouplist;
	}

	/** function to replace special characters
	 *  @ param $selectedfield : type string
	 *  this returns the string for grouplist
	 */

	function replaceSpecialChar($selectedfield){
		$selectedfield = decode_html(decode_html($selectedfield));
		preg_match('/&/', $selectedfield, $matches);
		if(!empty($matches)){
			$selectedfield = str_replace('&', 'and',($selectedfield));
		}
		return $selectedfield;
		}

	/** function to get the selectedorderbylist for the given reportid
	 *  @ param $reportid : type integer
	 *  this returns the columns query for the sortorder columns
	 *  this function also sets the return value in the class variable $this->orderbylistsql
	 */


	function getSelectedOrderbyList($reportid)
	{

		global $adb;
		global $modules;
		global $log;

		$sreportsortsql = "select ncrm_reportsortcol.* from ncrm_report";
		$sreportsortsql .= " inner join ncrm_reportsortcol on ncrm_report.reportid = ncrm_reportsortcol.reportid";
		$sreportsortsql .= " where ncrm_report.reportid =? order by ncrm_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$sortorder = $adb->query_result($result,$i,"sortorder");

			if($sortorder == "Ascending")
			{
				$sortorder = "ASC";
			}
			elseif($sortorder == "Descending")
			{
				$sortorder = "DESC";
			}

			if($fieldcolname != "none")
			{
				$this->orderbylistcolumns[] = $fieldcolname;
				$n = $n + 1;
				$selectedfields = explode(":",$fieldcolname);
				if($n > 1)
				{
					$sSQL .= ", ";
					$this->orderbylistsql .= ", ";
				}
				if($selectedfields[0] == "ncrm_crmentity".$this->primarymodule)
					$selectedfields[0] = "ncrm_crmentity";
				$sSQL .= $selectedfields[0].".".$selectedfields[1]." ".$sortorder;
				$this->orderbylistsql .= $selectedfields[0].".".$selectedfields[1]." ".$selectedfields[2];
			}
		}
		$log->info("ReportRun :: Successfully returned getSelectedOrderbyList".$reportid);
		return $sSQL;
	}

	/** function to get secondary Module for the given Primary module and secondary module
	 *  @ param $module : type String
	 *  @ param $secmodule : type String
	 *  this returns join query for the given secondary module
	 */

	function getRelatedModulesQuery($module,$secmodule)
	{
		global $log,$current_user;
		$query = '';
		if($secmodule!=''){
			$secondarymodule = explode(":",$secmodule);
			foreach($secondarymodule as $key=>$value) {
					$foc = CRMEntity::getInstance($value);

					// Case handling: Force table requirement ahead of time.
					$this->queryPlanner->addTable('ncrm_crmentity'. $value);

					$focQuery = $foc->generateReportsSecQuery($module,$value, $this->queryPlanner);

                    if ($focQuery) {
                        if(count($secondarymodule) > 1){
                            $query .= $focQuery . $this->getReportsNonAdminAccessControlQuery($value,$current_user,$value);
                        }else{
                            $query .= $focQuery . getNonAdminAccessControlQuery($value,$current_user,$value);;
                        }
					}
			}
		}
		$log->info("ReportRun :: Successfully returned getRelatedModulesQuery".$secmodule);

		return $query;

	}

    /**
     * Non admin user not able to see the records of report even he has permission
     * Fix for Case :- Report with One Primary Module, and Two Secondary modules, let's say for one of the
     * secondary module, non-admin user don't have permission, then reports is not showing the record even
     * the user has permission for another seconday module.
     * @param type $module
     * @param type $user
     * @param type $scope
     * @return $query
     */
    function getReportsNonAdminAccessControlQuery($module, $user, $scope = '') {
		require('user_privileges/user_privileges_' . $user->id . '.php');
		require('user_privileges/sharing_privileges_' . $user->id . '.php');
		$query = ' ';
		$tabId = getTabid($module);
		if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2]
				== 1 && $defaultOrgSharingPermission[$tabId] == 3) {
			$sharingRuleInfoVariable = $module . '_share_read_permission';
			$sharingRuleInfo = $$sharingRuleInfoVariable;
			$sharedTabId = null;

            if($module == "Calendar"){
                $sharedTabId = $tabId;
                $tableName = 'vt_tmp_u'.$user->id.'_t'.$tabId;
            }else if(!empty($sharingRuleInfo) && (count($sharingRuleInfo['ROLE']) > 0 ||
                    count($sharingRuleInfo['GROUP']) > 0)) {
                $sharedTabId = $tabId;
            }

            if (!empty($sharedTabId)) {
                $module = getTabModuleName($sharedTabId);
                if($module == "Calendar"){
                    // For calendar we have some special case to check like, calendar shared type
                    $moduleInstance = CRMEntity::getInstance($module);
                    $query = $moduleInstance->getReportsNonAdminAccessControlQuery($tableName, $tabId, $user, $current_user_parent_role_seq,$current_user_groups);
                }else{
                    $query = $this->getNonAdminAccessQuery($module, $user, $current_user_parent_role_seq, $current_user_groups);
                }

                $db = PearDatabase::getInstance();
                $result = $db->pquery($query, array());
                $rows = $db->num_rows($result);
                for($i=0; $i<$rows; $i++) {
                    $ids[] = $db->query_result($result, $i, 'id');
                }
                if(!empty($ids)) {
                    $query = " AND ncrm_crmentity$scope.smownerid IN (".implode(',', $ids).") ";
                }
            }
		}
		return $query;
	}


    /** function to get report query for the given module
	 *  @ param $module : type String
	 *  this returns join query for the given module
	 */

	function getReportsQuery($module, $type='')
	{
		global $log, $current_user;
		$secondary_module ="'";
		$secondary_module .= str_replace(":","','",$this->secondarymodule);
		$secondary_module .="'";

		if($module == "Leads")
		{
			$query = "from ncrm_leaddetails
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_leaddetails.leadid";

			if ($this->queryPlanner->requireTable('ncrm_leadsubdetails')) {
				$query .= "	inner join ncrm_leadsubdetails on ncrm_leadsubdetails.leadsubscriptionid=ncrm_leaddetails.leadid";
			}
			if ($this->queryPlanner->requireTable('ncrm_leadaddress')) {
				$query .= "	inner join ncrm_leadaddress on ncrm_leadaddress.leadaddressid=ncrm_leaddetails.leadid";
			}
			if ($this->queryPlanner->requireTable('ncrm_leadscf')) {
				$query .= " inner join ncrm_leadscf on ncrm_leaddetails.leadid = ncrm_leadscf.leadid";
			}
			if ($this->queryPlanner->requireTable('ncrm_groupsLeads')) {
				$query .= "	left join ncrm_groups as ncrm_groupsLeads on ncrm_groupsLeads.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('ncrm_usersLeads')) {
				$query .= " left join ncrm_users as ncrm_usersLeads on ncrm_usersLeads.id = ncrm_crmentity.smownerid";
			}

			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('ncrm_lastModifiedByLeads')) {
				$query .= " left join ncrm_users as ncrm_lastModifiedByLeads on ncrm_lastModifiedByLeads.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyLeads')){
                $query .= " left join ncrm_users as ncrm_createdbyLeads on ncrm_createdbyLeads.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " " . $this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0 and ncrm_leaddetails.converted=0";
		}
		else if($module == "Accounts")
		{
			$query = "from ncrm_account
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_account.accountid";

			if ($this->queryPlanner->requireTable('ncrm_accountbillads')) {
				$query .= " inner join ncrm_accountbillads on ncrm_account.accountid=ncrm_accountbillads.accountaddressid";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountshipads')) {
				$query .= " inner join ncrm_accountshipads on ncrm_account.accountid=ncrm_accountshipads.accountaddressid";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountscf')) {
				$query .= " inner join ncrm_accountscf on ncrm_account.accountid = ncrm_accountscf.accountid";
			}
			if ($this->queryPlanner->requireTable('ncrm_groupsAccounts')) {
				$query .= " left join ncrm_groups as ncrm_groupsAccounts on ncrm_groupsAccounts.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountAccounts')) {
				$query .= "	left join ncrm_account as ncrm_accountAccounts on ncrm_accountAccounts.accountid = ncrm_account.parentid";
			}
			if ($this->queryPlanner->requireTable('ncrm_usersAccounts')) {
				$query .= " left join ncrm_users as ncrm_usersAccounts on ncrm_usersAccounts.id = ncrm_crmentity.smownerid";
			}

			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid
				left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('ncrm_lastModifiedByAccounts')) {
				$query.= " left join ncrm_users as ncrm_lastModifiedByAccounts on ncrm_lastModifiedByAccounts.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyAccounts')){
                $query .= " left join ncrm_users as ncrm_createdbyAccounts on ncrm_createdbyAccounts.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0 ";
		}

		else if($module == "Contacts")
		{
			$query = "from ncrm_contactdetails
				inner join ncrm_crmentity on ncrm_crmentity.crmid = ncrm_contactdetails.contactid";

			if ($this->queryPlanner->requireTable('ncrm_contactaddress')) {
				$query .= "	inner join ncrm_contactaddress on ncrm_contactdetails.contactid = ncrm_contactaddress.contactaddressid";
			}
			if ($this->queryPlanner->requireTable('ncrm_customerdetails')) {
				$query .= "	inner join ncrm_customerdetails on ncrm_customerdetails.customerid = ncrm_contactdetails.contactid";
			}
			if ($this->queryPlanner->requireTable('ncrm_contactsubdetails')) {
				$query .= "	inner join ncrm_contactsubdetails on ncrm_contactdetails.contactid = ncrm_contactsubdetails.contactsubscriptionid";
			}
			if ($this->queryPlanner->requireTable('ncrm_contactscf')) {
				$query .= "	inner join ncrm_contactscf on ncrm_contactdetails.contactid = ncrm_contactscf.contactid";
			}
			if ($this->queryPlanner->requireTable('ncrm_groupsContacts')) {
				$query .= " left join ncrm_groups ncrm_groupsContacts on ncrm_groupsContacts.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('ncrm_contactdetailsContacts')) {
				$query .= "	left join ncrm_contactdetails as ncrm_contactdetailsContacts on ncrm_contactdetailsContacts.contactid = ncrm_contactdetails.reportsto";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountContacts')) {
				$query .= "	left join ncrm_account as ncrm_accountContacts on ncrm_accountContacts.accountid = ncrm_contactdetails.accountid";
			}
			if ($this->queryPlanner->requireTable('ncrm_usersContacts')) {
				$query .= " left join ncrm_users as ncrm_usersContacts on ncrm_usersContacts.id = ncrm_crmentity.smownerid";
			}

			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid
				left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('ncrm_lastModifiedByContacts')) {
			        $query .= " left join ncrm_users as ncrm_lastModifiedByContacts on ncrm_lastModifiedByContacts.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyContacts')){
                $query .= " left join ncrm_users as ncrm_createdbyContacts on ncrm_createdbyContacts.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0";
		}

		else if($module == "Potentials")
		{
			$query = "from ncrm_potential
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_potential.potentialid";

			if ($this->queryPlanner->requireTable('ncrm_potentialscf')) {
				$query .= " inner join ncrm_potentialscf on ncrm_potentialscf.potentialid = ncrm_potential.potentialid";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountPotentials')) {
				$query .= " left join ncrm_account as ncrm_accountPotentials on ncrm_potential.related_to = ncrm_accountPotentials.accountid";
			}
			if ($this->queryPlanner->requireTable('ncrm_contactdetailsPotentials')) {
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsPotentials on ncrm_potential.contact_id = ncrm_contactdetailsPotentials.contactid";
			}
			if ($this->queryPlanner->requireTable('ncrm_campaignPotentials')) {
				$query .= " left join ncrm_campaign as ncrm_campaignPotentials on ncrm_potential.campaignid = ncrm_campaignPotentials.campaignid";
			}
			if ($this->queryPlanner->requireTable('ncrm_groupsPotentials')) {
				$query .= " left join ncrm_groups ncrm_groupsPotentials on ncrm_groupsPotentials.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('ncrm_usersPotentials')) {
				$query .= " left join ncrm_users as ncrm_usersPotentials on ncrm_usersPotentials.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('ncrm_lastModifiedByPotentials')) {
				$query .= " left join ncrm_users as ncrm_lastModifiedByPotentials on ncrm_lastModifiedByPotentials.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyPotentials')){
                $query .= " left join ncrm_users as ncrm_createdbyPotentials on ncrm_createdbyPotentials.id = ncrm_crmentity.smcreatorid";
            }
			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0 ";
		}

		//For this Product - we can related Accounts, Contacts (Also Leads, Potentials)
		else if($module == "Products")
		{
			$query .= " from ncrm_products";
	    		$query .= " inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_products.productid";
			if ($this->queryPlanner->requireTable("ncrm_productcf")){
			    $query .= " left join ncrm_productcf on ncrm_products.productid = ncrm_productcf.productid";
			}
			if ($this->queryPlanner->requireTable("ncrm_lastModifiedByProducts")){
			    $query .= " left join ncrm_users as ncrm_lastModifiedByProducts on ncrm_lastModifiedByProducts.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyProducts')){
                $query .= " left join ncrm_users as ncrm_createdbyProducts on ncrm_createdbyProducts.id = ncrm_crmentity.smcreatorid";
            }
			if ($this->queryPlanner->requireTable("ncrm_usersProducts")){
			    $query .= " left join ncrm_users as ncrm_usersProducts on ncrm_usersProducts.id = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_groupsProducts")){
			    $query .= " left join ncrm_groups as ncrm_groupsProducts on ncrm_groupsProducts.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_vendorRelProducts")){
			    $query .= " left join ncrm_vendor as ncrm_vendorRelProducts on ncrm_vendorRelProducts.vendorid = ncrm_products.vendor_id";
			}
			if ($this->queryPlanner->requireTable("innerProduct")){
			    $query .= " LEFT JOIN (
						SELECT ncrm_products.productid,
								(CASE WHEN (ncrm_products.currency_id = 1 ) THEN ncrm_products.unit_price
									ELSE (ncrm_products.unit_price / ncrm_currency_info.conversion_rate) END
								) AS actual_unit_price
						FROM ncrm_products
						LEFT JOIN ncrm_currency_info ON ncrm_products.currency_id = ncrm_currency_info.id
						LEFT JOIN ncrm_productcurrencyrel ON ncrm_products.productid = ncrm_productcurrencyrel.productid
						AND ncrm_productcurrencyrel.currencyid = ". $current_user->currency_id . "
				) AS innerProduct ON innerProduct.productid = ncrm_products.productid";
			}
			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
						getNonAdminAccessControlQuery($this->primarymodule,$current_user)."
				where ncrm_crmentity.deleted=0";
		}

		else if($module == "HelpDesk")
		{
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('ncrm_crmentityRelHelpDesk',array('ncrm_accountRelHelpDesk','ncrm_contactdetailsRelHelpDesk'));

			$query = "from ncrm_troubletickets inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_troubletickets.ticketid";

			if ($this->queryPlanner->requireTable('ncrm_ticketcf')) {
				$query .= " inner join ncrm_ticketcf on ncrm_ticketcf.ticketid = ncrm_troubletickets.ticketid";
			}
			if ($this->queryPlanner->requireTable('ncrm_crmentityRelHelpDesk', $matrix)) {
				$query .= " left join ncrm_crmentity as ncrm_crmentityRelHelpDesk on ncrm_crmentityRelHelpDesk.crmid = ncrm_troubletickets.parent_id";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountRelHelpDesk')) {
				$query .= " left join ncrm_account as ncrm_accountRelHelpDesk on ncrm_accountRelHelpDesk.accountid=ncrm_crmentityRelHelpDesk.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_contactdetailsRelHelpDesk')) {
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsRelHelpDesk on ncrm_contactdetailsRelHelpDesk.contactid= ncrm_troubletickets.contact_id";
			}
			if ($this->queryPlanner->requireTable('ncrm_productsRel')) {
				$query .= " left join ncrm_products as ncrm_productsRel on ncrm_productsRel.productid = ncrm_troubletickets.product_id";
			}
			if ($this->queryPlanner->requireTable('ncrm_groupsHelpDesk')) {
				$query .= " left join ncrm_groups as ncrm_groupsHelpDesk on ncrm_groupsHelpDesk.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('ncrm_usersHelpDesk')) {
				$query .= " left join ncrm_users as ncrm_usersHelpDesk on ncrm_crmentity.smownerid=ncrm_usersHelpDesk.id";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_crmentity.smownerid=ncrm_users.id";

			if ($this->queryPlanner->requireTable('ncrm_lastModifiedByHelpDesk')) {
				$query .= "  left join ncrm_users as ncrm_lastModifiedByHelpDesk on ncrm_lastModifiedByHelpDesk.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyHelpDesk')){
                $query .= " left join ncrm_users as ncrm_createdbyHelpDesk on ncrm_createdbyHelpDesk.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0 ";
		}

		else if($module == "Calendar")
		{

			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('ncrm_cntactivityrel', array('ncrm_contactdetailsCalendar'));
			$matrix->setDependency('ncrm_seactivityrel', array('ncrm_crmentityRelCalendar'));
			$matrix->setDependency('ncrm_crmentityRelCalendar', array('ncrm_accountRelCalendar',
				'ncrm_leaddetailsRelCalendar','ncrm_potentialRelCalendar','ncrm_quotesRelCalendar',
				'ncrm_purchaseorderRelCalendar', 'ncrm_invoiceRelCalendar', 'ncrm_salesorderRelCalendar',
				'ncrm_troubleticketsRelCalendar', 'ncrm_campaignRelCalendar'
			));

			$query = "from ncrm_activity
				inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_activity.activityid";

			if ($this->queryPlanner->requireTable('ncrm_activitycf')) {
				$query .= " left join ncrm_activitycf on ncrm_activitycf.activityid = ncrm_crmentity.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_cntactivityrel', $matrix)) {
				$query .= " left join ncrm_cntactivityrel on ncrm_cntactivityrel.activityid= ncrm_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('ncrm_contactdetailsCalendar')) {
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsCalendar on ncrm_contactdetailsCalendar.contactid= ncrm_cntactivityrel.contactid";
			}
			if ($this->queryPlanner->requireTable('ncrm_groupsCalendar')) {
				$query .= " left join ncrm_groups as ncrm_groupsCalendar on ncrm_groupsCalendar.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('ncrm_usersCalendar')) {
				$query .= " left join ncrm_users as ncrm_usersCalendar on ncrm_usersCalendar.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('ncrm_seactivityrel', $matrix)) {
				$query .= " left join ncrm_seactivityrel on ncrm_seactivityrel.activityid = ncrm_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('ncrm_activity_reminder')) {
				$query .= " left join ncrm_activity_reminder on ncrm_activity_reminder.activity_id = ncrm_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('ncrm_recurringevents')) {
				$query .= " left join ncrm_recurringevents on ncrm_recurringevents.activityid = ncrm_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('ncrm_crmentityRelCalendar', $matrix)) {
				$query .= " left join ncrm_crmentity as ncrm_crmentityRelCalendar on ncrm_crmentityRelCalendar.crmid = ncrm_seactivityrel.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_accountRelCalendar')) {
				$query .= " left join ncrm_account as ncrm_accountRelCalendar on ncrm_accountRelCalendar.accountid=ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_leaddetailsRelCalendar')) {
				$query .= " left join ncrm_leaddetails as ncrm_leaddetailsRelCalendar on ncrm_leaddetailsRelCalendar.leadid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_potentialRelCalendar')) {
				$query .= " left join ncrm_potential as ncrm_potentialRelCalendar on ncrm_potentialRelCalendar.potentialid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_quotesRelCalendar')) {
				$query .= " left join ncrm_quotes as ncrm_quotesRelCalendar on ncrm_quotesRelCalendar.quoteid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_purchaseorderRelCalendar')) {
				$query .= " left join ncrm_purchaseorder as ncrm_purchaseorderRelCalendar on ncrm_purchaseorderRelCalendar.purchaseorderid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_invoiceRelCalendar')) {
				$query .= " left join ncrm_invoice as ncrm_invoiceRelCalendar on ncrm_invoiceRelCalendar.invoiceid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_salesorderRelCalendar')) {
				$query .= " left join ncrm_salesorder as ncrm_salesorderRelCalendar on ncrm_salesorderRelCalendar.salesorderid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_troubleticketsRelCalendar')) {
				$query .= " left join ncrm_troubletickets as ncrm_troubleticketsRelCalendar on ncrm_troubleticketsRelCalendar.ticketid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_campaignRelCalendar')) {
				$query .= " left join ncrm_campaign as ncrm_campaignRelCalendar on ncrm_campaignRelCalendar.campaignid = ncrm_crmentityRelCalendar.crmid";
			}
			if ($this->queryPlanner->requireTable('ncrm_lastModifiedByCalendar')) {
				$query .= " left join ncrm_users as ncrm_lastModifiedByCalendar on ncrm_lastModifiedByCalendar.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyCalendar')){
                $query .= " left join ncrm_users as ncrm_createdbyCalendar on ncrm_createdbyCalendar.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" WHERE ncrm_crmentity.deleted=0 and (ncrm_activity.activitytype != 'Emails')";
		}

		else if($module == "Quotes")
		{
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('ncrm_inventoryproductrelQuotes',array('ncrm_productsQuotes','ncrm_serviceQuotes'));

			$query = "from ncrm_quotes
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_quotes.quoteid";

			if ($this->queryPlanner->requireTable('ncrm_quotesbillads')){
				$query .= " inner join ncrm_quotesbillads on ncrm_quotes.quoteid=ncrm_quotesbillads.quotebilladdressid";
			}
			if ($this->queryPlanner->requireTable('ncrm_quotesshipads')){
				$query .= " inner join ncrm_quotesshipads on ncrm_quotes.quoteid=ncrm_quotesshipads.quoteshipaddressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_currency_info$module")){
				$query .= " left join ncrm_currency_info as ncrm_currency_info$module on ncrm_currency_info$module.id = ncrm_quotes.currency_id";
			}
			if($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				if ($this->queryPlanner->requireTable("ncrm_inventoryproductrelQuotes", $matrix)){
					$query .= " left join ncrm_inventoryproductrel as ncrm_inventoryproductrelQuotes on ncrm_quotes.quoteid = ncrm_inventoryproductrelQuotes.id";
				}
				if ($this->queryPlanner->requireTable("ncrm_productsQuotes")){
					$query .= " left join ncrm_products as ncrm_productsQuotes on ncrm_productsQuotes.productid = ncrm_inventoryproductrelQuotes.productid";
				}
				if ($this->queryPlanner->requireTable("ncrm_serviceQuotes")){
					$query .= " left join ncrm_service as ncrm_serviceQuotes on ncrm_serviceQuotes.serviceid = ncrm_inventoryproductrelQuotes.productid";
				}
			}
			if ($this->queryPlanner->requireTable("ncrm_quotescf")){
				$query .= " left join ncrm_quotescf on ncrm_quotes.quoteid = ncrm_quotescf.quoteid";
			}
			if ($this->queryPlanner->requireTable("ncrm_groupsQuotes")){
				$query .= " left join ncrm_groups as ncrm_groupsQuotes on ncrm_groupsQuotes.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_usersQuotes")){
				$query .= " left join ncrm_users as ncrm_usersQuotes on ncrm_usersQuotes.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("ncrm_lastModifiedByQuotes")){
				$query .= " left join ncrm_users as ncrm_lastModifiedByQuotes on ncrm_lastModifiedByQuotes.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyQuotes')){
                $query .= " left join ncrm_users as ncrm_createdbyQuotes on ncrm_createdbyQuotes.id = ncrm_crmentity.smcreatorid";
            }
			if ($this->queryPlanner->requireTable("ncrm_usersRel1")){
				$query .= " left join ncrm_users as ncrm_usersRel1 on ncrm_usersRel1.id = ncrm_quotes.inventorymanager";
			}
			if ($this->queryPlanner->requireTable("ncrm_potentialRelQuotes")){
				$query .= " left join ncrm_potential as ncrm_potentialRelQuotes on ncrm_potentialRelQuotes.potentialid = ncrm_quotes.potentialid";
			}
			if ($this->queryPlanner->requireTable("ncrm_contactdetailsQuotes")){
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsQuotes on ncrm_contactdetailsQuotes.contactid = ncrm_quotes.contactid";
			}
			if ($this->queryPlanner->requireTable("ncrm_accountQuotes")){
				$query .= " left join ncrm_account as ncrm_accountQuotes on ncrm_accountQuotes.accountid = ncrm_quotes.accountid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0";
		}

		else if($module == "PurchaseOrder")
		{

			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('ncrm_inventoryproductrelPurchaseOrder',array('ncrm_productsPurchaseOrder','ncrm_servicePurchaseOrder'));

			$query = "from ncrm_purchaseorder
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_purchaseorder.purchaseorderid";

			if ($this->queryPlanner->requireTable("ncrm_pobillads")){
				$query .= " inner join ncrm_pobillads on ncrm_purchaseorder.purchaseorderid=ncrm_pobillads.pobilladdressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_poshipads")){
				$query .= " inner join ncrm_poshipads on ncrm_purchaseorder.purchaseorderid=ncrm_poshipads.poshipaddressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_currency_info$module")){
				$query .= " left join ncrm_currency_info as ncrm_currency_info$module on ncrm_currency_info$module.id = ncrm_purchaseorder.currency_id";
			}
			if($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				if ($this->queryPlanner->requireTable("ncrm_inventoryproductrelPurchaseOrder",$matrix)){
					$query .= " left join ncrm_inventoryproductrel as ncrm_inventoryproductrelPurchaseOrder on ncrm_purchaseorder.purchaseorderid = ncrm_inventoryproductrelPurchaseOrder.id";
				}
				if ($this->queryPlanner->requireTable("ncrm_productsPurchaseOrder")){
					$query .= " left join ncrm_products as ncrm_productsPurchaseOrder on ncrm_productsPurchaseOrder.productid = ncrm_inventoryproductrelPurchaseOrder.productid";
				}
				if ($this->queryPlanner->requireTable("ncrm_servicePurchaseOrder")){
					$query .= " left join ncrm_service as ncrm_servicePurchaseOrder on ncrm_servicePurchaseOrder.serviceid = ncrm_inventoryproductrelPurchaseOrder.productid";
				}
			}
			if ($this->queryPlanner->requireTable("ncrm_purchaseordercf")){
				$query .= " left join ncrm_purchaseordercf on ncrm_purchaseorder.purchaseorderid = ncrm_purchaseordercf.purchaseorderid";
			}
			if ($this->queryPlanner->requireTable("ncrm_groupsPurchaseOrder")){
				$query .= " left join ncrm_groups as ncrm_groupsPurchaseOrder on ncrm_groupsPurchaseOrder.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_usersPurchaseOrder")){
				$query .= " left join ncrm_users as ncrm_usersPurchaseOrder on ncrm_usersPurchaseOrder.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("ncrm_lastModifiedByPurchaseOrder")){
				$query .= " left join ncrm_users as ncrm_lastModifiedByPurchaseOrder on ncrm_lastModifiedByPurchaseOrder.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyPurchaseOrder')){
                $query .= " left join ncrm_users as ncrm_createdbyPurchaseOrder on ncrm_createdbyPurchaseOrder.id = ncrm_crmentity.smcreatorid";
            }
			if ($this->queryPlanner->requireTable("ncrm_vendorRelPurchaseOrder")){
				$query .= " left join ncrm_vendor as ncrm_vendorRelPurchaseOrder on ncrm_vendorRelPurchaseOrder.vendorid = ncrm_purchaseorder.vendorid";
			}
			if ($this->queryPlanner->requireTable("ncrm_contactdetailsPurchaseOrder")){
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsPurchaseOrder on ncrm_contactdetailsPurchaseOrder.contactid = ncrm_purchaseorder.contactid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0";
		}

		else if($module == "Invoice")
		{
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('ncrm_inventoryproductrelInvoice',array('ncrm_productsInvoice','ncrm_serviceInvoice'));

			$query = "from ncrm_invoice
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_invoice.invoiceid";

			if ($this->queryPlanner->requireTable("ncrm_invoicebillads")){
				$query .=" inner join ncrm_invoicebillads on ncrm_invoice.invoiceid=ncrm_invoicebillads.invoicebilladdressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_invoiceshipads")){
				$query .=" inner join ncrm_invoiceshipads on ncrm_invoice.invoiceid=ncrm_invoiceshipads.invoiceshipaddressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_currency_info$module")){
				$query .=" left join ncrm_currency_info as ncrm_currency_info$module on ncrm_currency_info$module.id = ncrm_invoice.currency_id";
			}
			// lineItemFieldsInCalculation - is used to when line item fields are used in calculations
			if($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
			// should be present on when line item fields are selected for calculation
				if ($this->queryPlanner->requireTable("ncrm_inventoryproductrelInvoice",$matrix)){
					$query .=" left join ncrm_inventoryproductrel as ncrm_inventoryproductrelInvoice on ncrm_invoice.invoiceid = ncrm_inventoryproductrelInvoice.id";
				}
				if ($this->queryPlanner->requireTable("ncrm_productsInvoice")){
					$query .=" left join ncrm_products as ncrm_productsInvoice on ncrm_productsInvoice.productid = ncrm_inventoryproductrelInvoice.productid";
				}
				if ($this->queryPlanner->requireTable("ncrm_serviceInvoice")){
					$query .=" left join ncrm_service as ncrm_serviceInvoice on ncrm_serviceInvoice.serviceid = ncrm_inventoryproductrelInvoice.productid";
				}
			}
			if ($this->queryPlanner->requireTable("ncrm_salesorderInvoice")){
				$query .= " left join ncrm_salesorder as ncrm_salesorderInvoice on ncrm_salesorderInvoice.salesorderid=ncrm_invoice.salesorderid";
			}
			if ($this->queryPlanner->requireTable("ncrm_invoicecf")){
				$query .= " left join ncrm_invoicecf on ncrm_invoice.invoiceid = ncrm_invoicecf.invoiceid";
			}
			if ($this->queryPlanner->requireTable("ncrm_groupsInvoice")){
				$query .= " left join ncrm_groups as ncrm_groupsInvoice on ncrm_groupsInvoice.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_usersInvoice")){
				$query .= " left join ncrm_users as ncrm_usersInvoice on ncrm_usersInvoice.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("ncrm_lastModifiedByInvoice")){
				$query .= " left join ncrm_users as ncrm_lastModifiedByInvoice on ncrm_lastModifiedByInvoice.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbyInvoice')){
                $query .= " left join ncrm_users as ncrm_createdbyInvoice on ncrm_createdbyInvoice.id = ncrm_crmentity.smcreatorid";
            }
			if ($this->queryPlanner->requireTable("ncrm_accountInvoice")){
				$query .= " left join ncrm_account as ncrm_accountInvoice on ncrm_accountInvoice.accountid = ncrm_invoice.accountid";
			}
			if ($this->queryPlanner->requireTable("ncrm_contactdetailsInvoice")){
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsInvoice on ncrm_contactdetailsInvoice.contactid = ncrm_invoice.contactid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0";
		}
		else if($module == "SalesOrder")
		{
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('ncrm_inventoryproductrelSalesOrder',array('ncrm_productsSalesOrder','ncrm_serviceSalesOrder'));

			$query = "from ncrm_salesorder
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_salesorder.salesorderid";

			if ($this->queryPlanner->requireTable("ncrm_sobillads")){
				$query .= " inner join ncrm_sobillads on ncrm_salesorder.salesorderid=ncrm_sobillads.sobilladdressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_soshipads")){
				$query .= " inner join ncrm_soshipads on ncrm_salesorder.salesorderid=ncrm_soshipads.soshipaddressid";
			}
			if ($this->queryPlanner->requireTable("ncrm_currency_info$module")){
				$query .= " left join ncrm_currency_info as ncrm_currency_info$module on ncrm_currency_info$module.id = ncrm_salesorder.currency_id";
			}
			if($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				if ($this->queryPlanner->requireTable("ncrm_inventoryproductrelSalesOrder",$matrix)){
					$query .= " left join ncrm_inventoryproductrel as ncrm_inventoryproductrelSalesOrder on ncrm_salesorder.salesorderid = ncrm_inventoryproductrelSalesOrder.id";
				}
				if ($this->queryPlanner->requireTable("ncrm_productsSalesOrder")){
					$query .= " left join ncrm_products as ncrm_productsSalesOrder on ncrm_productsSalesOrder.productid = ncrm_inventoryproductrelSalesOrder.productid";
				}
				if ($this->queryPlanner->requireTable("ncrm_serviceSalesOrder")){
					$query .= " left join ncrm_service as ncrm_serviceSalesOrder on ncrm_serviceSalesOrder.serviceid = ncrm_inventoryproductrelSalesOrder.productid";
				}
			}
			if ($this->queryPlanner->requireTable("ncrm_salesordercf")){
				$query .=" left join ncrm_salesordercf on ncrm_salesorder.salesorderid = ncrm_salesordercf.salesorderid";
			}
			if ($this->queryPlanner->requireTable("ncrm_contactdetailsSalesOrder")){
				$query .= " left join ncrm_contactdetails as ncrm_contactdetailsSalesOrder on ncrm_contactdetailsSalesOrder.contactid = ncrm_salesorder.contactid";
			}
			if ($this->queryPlanner->requireTable("ncrm_quotesSalesOrder")){
				$query .= " left join ncrm_quotes as ncrm_quotesSalesOrder on ncrm_quotesSalesOrder.quoteid = ncrm_salesorder.quoteid";
			}
			if ($this->queryPlanner->requireTable("ncrm_accountSalesOrder")){
				$query .= " left join ncrm_account as ncrm_accountSalesOrder on ncrm_accountSalesOrder.accountid = ncrm_salesorder.accountid";
			}
			if ($this->queryPlanner->requireTable("ncrm_potentialRelSalesOrder")){
				$query .= " left join ncrm_potential as ncrm_potentialRelSalesOrder on ncrm_potentialRelSalesOrder.potentialid = ncrm_salesorder.potentialid";
			}
			if ($this->queryPlanner->requireTable("ncrm_invoice_recurring_info")){
				$query .= " left join ncrm_invoice_recurring_info on ncrm_invoice_recurring_info.salesorderid = ncrm_salesorder.salesorderid";
			}
			if ($this->queryPlanner->requireTable("ncrm_groupsSalesOrder")){
				$query .= " left join ncrm_groups as ncrm_groupsSalesOrder on ncrm_groupsSalesOrder.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_usersSalesOrder")){
				$query .= " left join ncrm_users as ncrm_usersSalesOrder on ncrm_usersSalesOrder.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("ncrm_lastModifiedBySalesOrder")){
				$query .= " left join ncrm_users as ncrm_lastModifiedBySalesOrder on ncrm_lastModifiedBySalesOrder.id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable('ncrm_createdbySalesOrder')){
                $query .= " left join ncrm_users as ncrm_createdbySalesOrder on ncrm_createdbySalesOrder.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0";
		}
		else if($module == "Campaigns")
		{
			$query = "from ncrm_campaign
			inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_campaign.campaignid";
			if ($this->queryPlanner->requireTable("ncrm_campaignscf")){
				$query .= " inner join ncrm_campaignscf as ncrm_campaignscf on ncrm_campaignscf.campaignid=ncrm_campaign.campaignid";
			}
			if ($this->queryPlanner->requireTable("ncrm_productsCampaigns")){
				$query .= " left join ncrm_products as ncrm_productsCampaigns on ncrm_productsCampaigns.productid = ncrm_campaign.product_id";
				}
			if ($this->queryPlanner->requireTable("ncrm_groupsCampaigns")){
				$query .= " left join ncrm_groups as ncrm_groupsCampaigns on ncrm_groupsCampaigns.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_usersCampaigns")){
				$query .= " left join ncrm_users as ncrm_usersCampaigns on ncrm_usersCampaigns.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("ncrm_lastModifiedBy$module")){
				$query .= " left join ncrm_users as ncrm_lastModifiedBy".$module." on ncrm_lastModifiedBy".$module.".id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable("ncrm_createdby$module")){
                $query .= " left join ncrm_users as ncrm_createdby$module on ncrm_createdby$module.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where ncrm_crmentity.deleted=0";
		}
		else if($module == "Emails") {
			$query = "from ncrm_activity
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_activity.activityid AND ncrm_activity.activitytype = 'Emails'";

			if ($this->queryPlanner->requireTable("ncrm_email_track")){
				$query .= " LEFT JOIN ncrm_email_track ON ncrm_email_track.mailid = ncrm_activity.activityid";
			}
			if ($this->queryPlanner->requireTable("ncrm_groupsEmails")){
				$query .= " LEFT JOIN ncrm_groups AS ncrm_groupsEmails ON ncrm_groupsEmails.groupid = ncrm_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("ncrm_usersEmails")){
				$query .= " LEFT JOIN ncrm_users AS ncrm_usersEmails ON ncrm_usersEmails.id = ncrm_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";
			$query .= " LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("ncrm_lastModifiedBy$module")){
				$query .= " LEFT JOIN ncrm_users AS ncrm_lastModifiedBy".$module." ON ncrm_lastModifiedBy".$module.".id = ncrm_crmentity.modifiedby";
			}
            if($this->queryPlanner->requireTable("ncrm_createdby$module")){
                $query .= " left join ncrm_users as ncrm_createdby$module on ncrm_createdby$module.id = ncrm_crmentity.smcreatorid";
            }

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" WHERE ncrm_crmentity.deleted = 0";

		}
		else {
			if($module!=''){
				$focus = CRMEntity::getInstance($module);
				$query = $focus->generateReportsQuery($module, $this->queryPlanner).
						$this->getRelatedModulesQuery($module,$this->secondarymodule).
						getNonAdminAccessControlQuery($this->primarymodule,$current_user).
						" WHERE ncrm_crmentity.deleted=0";
			}
		}
		$log->info("ReportRun :: Successfully returned getReportsQuery".$module);

		return $query;
	}


	/** function to get query for the given reportid,filterlist,type
	 *  @ param $reportid : Type integer
	 *  @ param $filtersql : Type Array
	 *  @ param $module : Type String
	 *  this returns join query for the report
	 */

	function sGetSQLforReport($reportid,$filtersql,$type='',$chartReport=false,$startLimit=false,$endLimit=false)
	{
		global $log;

		$columnlist = $this->getQueryColumnsList($reportid,$type);
		$groupslist = $this->getGroupingList($reportid);
		$groupTimeList = $this->getGroupByTimeList($reportid);
		$stdfilterlist = $this->getStdFilterList($reportid);
		$columnstotallist = $this->getColumnsTotal($reportid);
		$advfiltersql = $this->getAdvFilterSql($reportid);

		$this->totallist = $columnstotallist;
		global $current_user;
		//Fix for ticket #4915.
		$selectlist = $columnlist;
		//columns list
		if(isset($selectlist))
		{
			$selectedcolumns =  implode(", ",$selectlist);
			if($chartReport == true){
				$selectedcolumns .= ", count(*) AS 'groupby_count'";
			}
		}
		//groups list
		if(isset($groupslist))
		{
			$groupsquery = implode(", ",$groupslist);
		}
		if(isset($groupTimeList)){
           	$groupTimeQuery = implode(", ",$groupTimeList);
        }

		//standard list
		if(isset($stdfilterlist))
		{
			$stdfiltersql = implode(", ",$stdfilterlist);
		}
		//columns to total list
		if(isset($columnstotallist))
		{
			$columnstotalsql = implode(", ",$columnstotallist);
		}
		if($stdfiltersql != "")
		{
			$wheresql = " and ".$stdfiltersql;
		}

		if(isset($filtersql) && $filtersql !== false && $filtersql != '') {
			$advfiltersql = $filtersql;
		}
		if($advfiltersql != "") {
			$wheresql .= " and ".$advfiltersql;
		}

		$reportquery = $this->getReportsQuery($this->primarymodule, $type);

		// If we don't have access to any columns, let us select one column and limit result to shown we have not results
                // Fix for: http://trac.ncrm.com/cgi-bin/trac.cgi/ticket/4758 - Prasad
		$allColumnsRestricted = false;

		if($type == 'COLUMNSTOTOTAL')
		{
			if($columnstotalsql != '')
			{
				$reportquery = "select ".$columnstotalsql." ".$reportquery." ".$wheresql;
			}
		}else
		{
			if($selectedcolumns == '') {
				// Fix for: http://trac.ncrm.com/cgi-bin/trac.cgi/ticket/4758 - Prasad

				$selectedcolumns = "''"; // "''" to get blank column name
				$allColumnsRestricted = true;
			}
			$reportquery = "select DISTINCT ".$selectedcolumns." ".$reportquery." ".$wheresql;
		}
		$reportquery = listQueryNonAdminChange($reportquery, $this->primarymodule);

		if(trim($groupsquery) != "" && $type !== 'COLUMNSTOTOTAL')
		{
            if($chartReport == true){
                $reportquery .= "group by ".$this->GetFirstSortByField($reportid);
            }else{
                $reportquery .= " order by ".$groupsquery;
			}
		}

		// Prasad: No columns selected so limit the number of rows directly.
		if($allColumnsRestricted) {
			$reportquery .= " limit 0";
		} else if($startLimit !== false && $endLimit !== false) {
			$reportquery .= " LIMIT $startLimit, $endLimit";
		}

		preg_match('/&amp;/', $reportquery, $matches);
        if(!empty($matches)){
            $report=str_replace('&amp;', '&', $reportquery);
            $reportquery = $this->replaceSpecialChar($report);
        }
		$log->info("ReportRun :: Successfully returned sGetSQLforReport".$reportid);

		$this->queryPlanner->initializeTempTables();

		return $reportquery;

	}

	/** function to get the report output in HTML,PDF,TOTAL,PRINT,PRINTTOTAL formats depends on the argument $outputformat
	 *  @ param $outputformat : Type String (valid parameters HTML,PDF,TOTAL,PRINT,PRINT_TOTAL)
	 *  @ param $filtersql : Type String
	 *  This returns HTML Report if $outputformat is HTML
         *  		Array for PDF if  $outputformat is PDF
	 *		HTML strings for TOTAL if $outputformat is TOTAL
	 *		Array for PRINT if $outputformat is PRINT
	 *		HTML strings for TOTAL fields  if $outputformat is PRINTTOTAL
	 *		HTML strings for
	 */

	// Performance Optimization: Added parameter directOutput to avoid building big-string!
	function GenerateReport($outputformat,$filtersql, $directOutput=false, $startLimit=false, $endLimit=false)
	{
		global $adb,$current_user,$php_max_execution_time;
		global $modules,$app_strings;
		global $mod_strings,$current_language;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		$modules_selected = array();
		$modules_selected[] = $this->primarymodule;
		if(!empty($this->secondarymodule)){
			$sec_modules = split(":",$this->secondarymodule);
			for($i=0;$i<count($sec_modules);$i++){
				$modules_selected[] = $sec_modules[$i];
			}
		}

		// Update Reference fields list list
		$referencefieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from ncrm_field WHERE uitype in (10,101)", array());
		if($referencefieldres) {
			foreach($referencefieldres as $referencefieldrow) {
				$uiType = $referencefieldrow['uitype'];
				$modprefixedlabel = getTabModuleName($referencefieldrow['tabid']).' '.$referencefieldrow['fieldlabel'];
				$modprefixedlabel = str_replace(' ','_',$modprefixedlabel);

				if($uiType == 10 && !in_array($modprefixedlabel, $this->ui10_fields)) {
					$this->ui10_fields[] = $modprefixedlabel;
				} elseif($uiType == 101 && !in_array($modprefixedlabel, $this->ui101_fields)) {
					$this->ui101_fields[] = $modprefixedlabel;
				}
			}
		}

		if($outputformat == "HTML")
		{
			$sSQL = $this->sGetSQLforReport($this->reportid,$filtersql,$outputformat,false,$startLimit,$endLimit);
			$sSQL .= " LIMIT 0, " . (self::$HTMLVIEW_MAX_ROWS+1); // Pull a record more than limit

			$result = $adb->query($sSQL);
			$error_msg = $adb->database->ErrorMsg();
			if(!$result && $error_msg!=''){
				// Performance Optimization: If direct output is requried
				if($directOutput) {
					echo getTranslatedString('LBL_REPORT_GENERATION_FAILED', $currentModule) . "<br>" . $error_msg;
					$error_msg = false;
				}
				// END
				return $error_msg;
			}

			// Performance Optimization: If direct output is required
			if($directOutput) {
				echo '<table cellpadding="5" cellspacing="0" align="center" class="rptTable"><tr>';
			}
			// END

			if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
				$picklistarray = $this->getAccessPickListValues();
			if($result)
			{
				$y=$adb->num_fields($result);
				$arrayHeaders = Array();
				for ($x=0; $x<$y; $x++)
				{
					$fld = $adb->field_name($result, $x);
					if(in_array($this->getLstringforReportHeaders($fld->name), $arrayHeaders))
					{
						$headerLabel = str_replace("_"," ",$fld->name);
						$arrayHeaders[] = $headerLabel;
					}
					else
					{
						$headerLabel = str_replace($modules," ",$this->getLstringforReportHeaders($fld->name));
						$headerLabel = str_replace("_"," ",$this->getLstringforReportHeaders($fld->name));
						$arrayHeaders[] = $headerLabel;
					}
					/*STRING TRANSLATION starts */
					$mod_name = split(' ',$headerLabel,2);
					$moduleLabel ='';
					if(in_array($mod_name[0],$modules_selected)){
						$moduleLabel = getTranslatedString($mod_name[0],$mod_name[0]);
					}

					if(!empty($this->secondarymodule)){
						if($moduleLabel!=''){
							$headerLabel_tmp = $moduleLabel." ".getTranslatedString($mod_name[1],$mod_name[0]);
						} else {
							$headerLabel_tmp = getTranslatedString($mod_name[0]." ".$mod_name[1]);
						}
					} else {
						if($moduleLabel!=''){
							$headerLabel_tmp = getTranslatedString($mod_name[1],$mod_name[0]);
						} else {
							$headerLabel_tmp = getTranslatedString($mod_name[0]." ".$mod_name[1]);
						}
					}
					if($headerLabel == $headerLabel_tmp) $headerLabel = getTranslatedString($headerLabel_tmp);
					else $headerLabel = $headerLabel_tmp;
					/*STRING TRANSLATION ends */
					$header .= "<td class='rptCellLabel'>".$headerLabel."</td>";

					// Performance Optimization: If direct output is required
					if($directOutput) {
						echo $header;
						$header = '';
					}
					// END
				}

				// Performance Optimization: If direct output is required
				if($directOutput) {
					echo '</tr><tr>';
				}
				// END

				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				$groupslist = $this->getGroupingList($this->reportid);

				$column_definitions = $adb->getFieldsDefinition($result);

				do
				{
					$arraylists = Array();
					if(count($groupslist) == 1)
					{
						$newvalue = $custom_field_values[0];
					}elseif(count($groupslist) == 2)
					{
						$newvalue = $custom_field_values[0];
						$snewvalue = $custom_field_values[1];
					}elseif(count($groupslist) == 3)
					{
						$newvalue = $custom_field_values[0];
						$snewvalue = $custom_field_values[1];
						$tnewvalue = $custom_field_values[2];
					}
					if($newvalue == "") $newvalue = "-";

					if($snewvalue == "") $snewvalue = "-";

					if($tnewvalue == "") $tnewvalue = "-";

					$valtemplate .= "<tr>";

					// Performance Optimization
					if($directOutput) {
						echo $valtemplate;
						$valtemplate = '';
					}
					// END

					for ($i=0; $i<$y; $i++)
					{
						$fld = $adb->field_name($result, $i);
						$fld_type = $column_definitions[$i]->type;
						$fieldvalue = getReportFieldValue($this, $picklistarray, $fld,
								$custom_field_values, $i);

					//check for Roll based pick list
						$temp_val= $fld->name;

						if($fieldvalue == "" )
						{
							$fieldvalue = "-";
						}
						else if($fld->name == $this->primarymodule.'_LBL_ACTION' && $fieldvalue != '-')
						{
							$fieldvalue = "<a href='index.php?module={$this->primarymodule}&action=DetailView&record={$fieldvalue}' target='_blank'>".getTranslatedString('LBL_VIEW_DETAILS', 'Reports')."</a>";
						}

						if(($lastvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($this->reporttype == "summary")
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td class='rptData'>".$fieldvalue."</td>";
							}
						}else if(($secondvalue === $fieldvalue) && $this->reporttype == "summary")
						{
							if($lastvalue === $newvalue)
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}
						else if(($thirdvalue === $fieldvalue) && $this->reporttype == "summary")
						{
							if($secondvalue === $snewvalue)
							{
								$valtemplate .= "<td class='rptEmptyGrp'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}
						else
						{
							if($this->reporttype == "tabular")
							{
								$valtemplate .= "<td class='rptData'>".$fieldvalue."</td>";
							}else
							{
								$valtemplate .= "<td class='rptGrpHead'>".$fieldvalue."</td>";
							}
						}

						// Performance Optimization: If direct output is required
						if($directOutput) {
							echo $valtemplate;
							$valtemplate = '';
						}
						// END
					}

					$valtemplate .= "</tr>";

					// Performance Optimization: If direct output is required
					if($directOutput) {
						echo $valtemplate;
						$valtemplate = '';
					}
					// END

					$lastvalue = $newvalue;
					$secondvalue = $snewvalue;
					$thirdvalue = $tnewvalue;
					$arr_val[] = $arraylists;
					set_time_limit($php_max_execution_time);
				}while($custom_field_values = $adb->fetch_array($result));

				// Performance Optimization: Provide feedback on export option if required
				// NOTE: We should make sure to pull at-least 1 row more than max-limit for this to work.
				if ($noofrows > self::$HTMLVIEW_MAX_ROWS) {
					// Performance Optimization: Output directly
					if ($directOutput) {
						echo '</tr></table><br><table width="100%" cellpading="0" cellspacing="0"><tr>';
						echo sprintf('<td colspan="%s" align="right"><span class="genHeaderGray">%s</span></td>',
								$y, getTranslatedString('Only')." ".self::$HTMLVIEW_MAX_ROWS .
								"+ " . getTranslatedString('records found') . ". " . getTranslatedString('Export to') . " <a href=\"javascript:;\" onclick=\"goToURL(CrearEnlace('ReportsAjax&file=CreateCSV',{$this->reportid}));\"><img style='vertical-align:text-top' src='themes/images/csv-file.png'></a> /" .
								" <a href=\"javascript:;\" onclick=\"goToURL(CrearEnlace('CreateXL',{$this->reportid}));\"><img style='vertical-align:text-top' src='themes/images/xls-file.jpg'></a>"
								);

					} else {
						$valtemplate .= '</tr></table><br><table width="100%" cellpading="0" cellspacing="0"><tr>';
						$valtemplate .= sprintf('<td colspan="%s" align="right"><span class="genHeaderGray">%s</span></td>',
								$y, getTranslatedString('Only')." ".self::$HTMLVIEW_MAX_ROWS .
								" " . getTranslatedString('records found') . ". " . getTranslatedString('Export to') . " <a href=\"javascript:;\" onclick=\"goToURL(CrearEnlace('ReportsAjax&file=CreateCSV',{$this->reportid}));\"><img style='vertical-align:text-top' src='themes/images/csv-file.png'></a> /" .
								" <a href=\"javascript:;\" onclick=\"goToURL(CrearEnlace('CreateXL',{$this->reportid}));\"><img style='vertical-align:text-top' src='themes/images/xls-file.jpg'></a>"
								);
					}
				}


				// Performance Optimization
				if($directOutput) {

					$totalDisplayString = $noofrows;
					if ($noofrows > self::$HTMLVIEW_MAX_ROWS) {
						$totalDisplayString = self::$HTMLVIEW_MAX_ROWS . "+";
					}

					echo "</tr></table>";
					echo "<script type='text/javascript' id='__reportrun_directoutput_recordcount_script'>
						if($('_reportrun_total')) $('_reportrun_total').innerHTML='$totalDisplayString';</script>";
				} else {

					$sHTML ='<table cellpadding="5" cellspacing="0" align="center" class="rptTable">
					<tr>'.
					$header
					.'<!-- BEGIN values -->
					<tr>'.
					$valtemplate
					.'</tr>
					</table>';
				}
				//<<<<<<<<construct HTML>>>>>>>>>>>>
				$return_data[] = $sHTML;
				$return_data[] = $noofrows;
				$return_data[] = $sSQL;
				return $return_data;
			}
		}elseif($outputformat == "PDF")
		{

			$sSQL = $this->sGetSQLforReport($this->reportid,$filtersql,$outputformat,false,$startLimit,$endLimit);
			$result = $adb->pquery($sSQL,array());
			if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
                $picklistarray = $this->getAccessPickListValues();

			if($result)
			{
				$y=$adb->num_fields($result);
				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				$column_definitions = $adb->getFieldsDefinition($result);

				do
				{
					$arraylists = Array();
					for ($i=0; $i<$y; $i++)
					{
						$fld = $adb->field_name($result, $i);
						$fld_type = $column_definitions[$i]->type;
						list($module, $fieldLabel) = explode('_', $fld->name, 2);
						$fieldInfo = getFieldByReportLabel($module, $fieldLabel);
						$fieldType = null;
						if(!empty($fieldInfo)) {
							$field = WebserviceField::fromArray($adb, $fieldInfo);
							$fieldType = $field->getFieldDataType();
						}
						if(!empty($fieldInfo)) {
							$translatedLabel = getTranslatedString($field->getFieldLabelKey(),
									$module);
						} else {
							$translatedLabel = getTranslatedString($fieldLabel, $module);
                            $translatedLabel = str_replace("_", " ", $translatedLabel);
						}
						/*STRING TRANSLATION starts */
						$moduleLabel ='';
						if(in_array($module,$modules_selected))
							$moduleLabel = getTranslatedString($module,$module);

						if(empty($translatedLabel)) {
								$translatedLabel = getTranslatedString(str_replace('_', " ",
									$fld->name), $module);
						}
						$headerLabel = $translatedLabel;
						if(!empty($this->secondarymodule)) {
							if($moduleLabel != '') {
								$headerLabel = $moduleLabel." ". $translatedLabel;
							}
						}
						// Check for role based pick list
						$temp_val= $fld->name;
						$fieldvalue = getReportFieldValue($this, $picklistarray, $fld,
								$custom_field_values, $i);

						if($fld->name == $this->primarymodule.'_LBL_ACTION' && $fieldvalue != '-') {
							$fieldvalue = "<a href='index.php?module={$this->primarymodule}&view=Detail&record={$fieldvalue}' target='_blank'>".getTranslatedString('LBL_VIEW_DETAILS', 'Reports')."</a>";
						}

						$arraylists[$headerLabel] = $fieldvalue;
					}
					$arr_val[] = $arraylists;
					set_time_limit($php_max_execution_time);
				}while($custom_field_values = $adb->fetch_array($result));

                $data['data'] = $arr_val;
                $data['count'] = $noofrows;
                return $data;
			}
		}elseif($outputformat == "TOTALXLS")
		{
				$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
				$totalpdf=array();
				$sSQL = $this->sGetSQLforReport($this->reportid,$filtersql,"COLUMNSTOTOTAL");
				if(isset($this->totallist))
				{
						if($sSQL != "")
						{
								$result = $adb->query($sSQL);
								$y=$adb->num_fields($result);
								$custom_field_values = $adb->fetch_array($result);

								foreach($this->totallist as $key=>$value)
								{
									$fieldlist = explode(":",$key);
									$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from ncrm_field where tablename = ? and columnname=?",array($fieldlist[1],$fieldlist[2]));
									if($adb->num_rows($mod_query)>0){
											$module_name = getTabModuleName($adb->query_result($mod_query,0,'tabid'));
											$fieldlabel = trim(str_replace($escapedchars," ",$fieldlist[3]));
											$fieldlabel = str_replace("_", " ", $fieldlabel);
											if($module_name){
												$field = getTranslatedString($module_name,$module_name)." ".getTranslatedString($fieldlabel,$module_name);
											} else {
												$field = getTranslatedString($fieldlabel);
											}
									}
									// Since there are duplicate entries for this table
									if($fieldlist[1] == 'ncrm_inventoryproductrel') {
										$module_name = $this->primarymodule;
									}
									$uitype_arr[str_replace($escapedchars," ",$module_name."_".$fieldlist[3])] = $adb->query_result($mod_query,0,"uitype");
									$totclmnflds[str_replace($escapedchars," ",$module_name."_".$fieldlist[3])] = $field;
								}
								for($i =0;$i<$y;$i++)
								{
										$fld = $adb->field_name($result, $i);
										$keyhdr[$fld->name] = $custom_field_values[$i];
								}

								$rowcount=0;
								foreach($totclmnflds as $key=>$value)
								{
										$col_header = trim(str_replace($modules," ",$value));
										$fld_name_1 = $this->primarymodule . "_" . trim($value);
										$fld_name_2 = $this->secondarymodule . "_" . trim($value);
										if($uitype_arr[$key] == 71 || $uitype_arr[$key] == 72 ||
											in_array($fld_name_1,$this->append_currency_symbol_to_value) || in_array($fld_name_2,$this->append_currency_symbol_to_value)) {
												$col_header .= " (".$app_strings['LBL_IN']." ".$current_user->currency_symbol.")";
												$convert_price = true;
										} else{
												$convert_price = false;
										}
										$value = trim($key);
										$arraykey = $value.'_SUM';
										if(isset($keyhdr[$arraykey]))
										{
											if($convert_price)
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
											else
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
												$totalpdf[$rowcount][$arraykey] = $conv_value;
										}else
										{
												$totalpdf[$rowcount][$arraykey] = '';
										}

										$arraykey = $value.'_AVG';
										if(isset($keyhdr[$arraykey]))
										{
											if($convert_price)
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
											else
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
											$totalpdf[$rowcount][$arraykey] = $conv_value;
										}else
										{
												$totalpdf[$rowcount][$arraykey] = '';
										}

										$arraykey = $value.'_MIN';
										if(isset($keyhdr[$arraykey]))
										{
											if($convert_price)
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
											else
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
											$totalpdf[$rowcount][$arraykey] = $conv_value;
										}else
										{
												$totalpdf[$rowcount][$arraykey] = '';
										}

										$arraykey = $value.'_MAX';
										if(isset($keyhdr[$arraykey]))
										{
											if($convert_price)
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
											else
												$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
											$totalpdf[$rowcount][$arraykey] = $conv_value;
										}else
										{
												$totalpdf[$rowcount][$arraykey] = '';
										}
										$rowcount++;
								}
						}
				}
				return $totalpdf;
		}elseif($outputformat == "TOTALHTML")
		{
			$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
			$sSQL = $this->sGetSQLforReport($this->reportid,$filtersql,"COLUMNSTOTOTAL");

			static $modulename_cache = array();

			if(isset($this->totallist))
			{
				if($sSQL != "")
				{
					$result = $adb->query($sSQL);
					$y=$adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);
					$coltotalhtml .= "<table align='center' width='60%' cellpadding='3' cellspacing='0' border='0' class='rptTable'><tr><td class='rptCellLabel'>".$mod_strings[Totals]."</td><td class='rptCellLabel'>".$mod_strings[SUM]."</td><td class='rptCellLabel'>".$mod_strings[AVG]."</td><td class='rptCellLabel'>".$mod_strings[MIN]."</td><td class='rptCellLabel'>".$mod_strings[MAX]."</td></tr>";

					// Performation Optimization: If Direct output is desired
					if($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END

					foreach($this->totallist as $key=>$value)
					{
						$fieldlist = explode(":",$key);

						$module_name = NULL;
						$cachekey = $fieldlist[1] . ":" . $fieldlist[2];
						if (!isset($modulename_cache[$cachekey])) {
							$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from ncrm_field where tablename = ? and columnname=?",array($fieldlist[1],$fieldlist[2]));
							if($adb->num_rows($mod_query)>0){
								$module_name = getTabModuleName($adb->query_result($mod_query,0,'tabid'));
								$modulename_cache[$cachekey] = $module_name;
							}
						} else {
							$module_name = $modulename_cache[$cachekey];
						}
						if ($module_name) {
							$fieldlabel = trim(str_replace($escapedchars," ",$fieldlist[3]));
							$fieldlabel = str_replace("_", " ", $fieldlabel);
							$field = getTranslatedString($module_name, $module_name)." ".getTranslatedString($fieldlabel,$module_name);
						} else {
							$field = getTranslatedString($fieldlabel);
						}

						$uitype_arr[str_replace($escapedchars," ",$module_name."_".$fieldlist[3])] = $adb->query_result($mod_query,0,"uitype");
						$totclmnflds[str_replace($escapedchars," ",$module_name."_".$fieldlist[3])] = $field;
					}
					for($i =0;$i<$y;$i++)
					{
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];
					}

					foreach($totclmnflds as $key=>$value)
					{
						$coltotalhtml .= '<tr class="rptGrpHead" valign=top>';
						$col_header = trim(str_replace($modules," ",$value));
						$fld_name_1 = $this->primarymodule . "_" . trim($value);
						$fld_name_2 = $this->secondarymodule . "_" . trim($value);
						if($uitype_arr[$key]==71 || $uitype_arr[$key] == 72 ||
											in_array($fld_name_1,$this->append_currency_symbol_to_value) || in_array($fld_name_2,$this->append_currency_symbol_to_value)) {
							$col_header .= " (".$app_strings['LBL_IN']." ".$current_user->currency_symbol.")";
							$convert_price = true;
						} else{
							$convert_price = false;
						}
						$coltotalhtml .= '<td class="rptData">'. $col_header .'</td>';
						$value = trim($key);
						$arraykey = $value.'_SUM';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">'.$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = $value.'_AVG';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">'.$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = $value.'_MIN';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">'.$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = $value.'_MAX';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">'.$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$coltotalhtml .= '<tr>';

						// Performation Optimization: If Direct output is desired
						if($directOutput) {
							echo $coltotalhtml;
							$coltotalhtml = '';
						}
						// END
					}

					$coltotalhtml .= "</table>";

					// Performation Optimization: If Direct output is desired
					if($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END
				}
			}
			return $coltotalhtml;
		}elseif($outputformat == "PRINT")
		{
			$sSQL = $this->sGetSQLforReport($this->reportid,$filtersql, $outputformat);
			$result = $adb->query($sSQL);
			if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
			$picklistarray = $this->getAccessPickListValues();

			if($result)
			{
				$y=$adb->num_fields($result);
				$arrayHeaders = Array();
				for ($x=0; $x<$y-1; $x++)
				{
					$fld = $adb->field_name($result, $x);
					if(in_array($this->getLstringforReportHeaders($fld->name), $arrayHeaders))
					{
						$headerLabel = str_replace("_"," ",$fld->name);
						$arrayHeaders[] = $headerLabel;
					}
					else
					{
						$headerLabel = str_replace($modules," ",$this->getLstringforReportHeaders($fld->name));
                        $headerLabel = str_replace("_"," ",$this->getLstringforReportHeaders($fld->name));
						$arrayHeaders[] = $headerLabel;
					}
					/*STRING TRANSLATION starts */
					$mod_name = split(' ',$headerLabel,2);
					$moduleLabel ='';
					if(in_array($mod_name[0],$modules_selected)){
						$moduleLabel = getTranslatedString($mod_name[0],$mod_name[0]);
					}

					if(!empty($this->secondarymodule)){
						if($moduleLabel!=''){
							$headerLabel_tmp = $moduleLabel." ".getTranslatedString($mod_name[1],$mod_name[0]);
						} else {
							$headerLabel_tmp = getTranslatedString($mod_name[0]." ".$mod_name[1]);
						}
					} else {
						if($moduleLabel!=''){
							$headerLabel_tmp = getTranslatedString($mod_name[1],$mod_name[0]);
						} else {
							$headerLabel_tmp = getTranslatedString($mod_name[0]." ".$mod_name[1]);
						}
					}
					if($headerLabel == $headerLabel_tmp) $headerLabel = getTranslatedString($headerLabel_tmp);
					else $headerLabel = $headerLabel_tmp;
					/*STRING TRANSLATION ends */
					$header .= "<th>".$headerLabel."</th>";
				}
				$noofrows = $adb->num_rows($result);
				$custom_field_values = $adb->fetch_array($result);
				$groupslist = $this->getGroupingList($this->reportid);

				$column_definitions = $adb->getFieldsDefinition($result);

				do
				{
					$arraylists = Array();
					if(count($groupslist) == 1)
					{
						$newvalue = $custom_field_values[0];
					}elseif(count($groupslist) == 2)
					{
						$newvalue = $custom_field_values[0];
						$snewvalue = $custom_field_values[1];
					}elseif(count($groupslist) == 3)
					{
						$newvalue = $custom_field_values[0];
                                                $snewvalue = $custom_field_values[1];
						$tnewvalue = $custom_field_values[2];
					}

					if($newvalue == "") $newvalue = "-";

					if($snewvalue == "") $snewvalue = "-";

					if($tnewvalue == "") $tnewvalue = "-";

					$valtemplate .= "<tr>";

					for ($i=0; $i<$y-1; $i++)
					{
						$fld = $adb->field_name($result, $i);
						$fld_type = $column_definitions[$i]->type;
						$fieldvalue = getReportFieldValue($this, $picklistarray, $fld,
								$custom_field_values, $i);
						if(($lastvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($this->reporttype == "summary")
							{
								$valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}else if(($secondvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($lastvalue == $newvalue)
							{
								$valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}
						else if(($thirdvalue == $fieldvalue) && $this->reporttype == "summary")
						{
							if($secondvalue == $snewvalue)
							{
								$valtemplate .= "<td style='border-top:1px dotted #FFFFFF;'>&nbsp;</td>";
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}
						else
						{
							if($this->reporttype == "tabular")
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}else
							{
								$valtemplate .= "<td>".$fieldvalue."</td>";
							}
						}
					  }
					 $valtemplate .= "</tr>";
					 $lastvalue = $newvalue;
					 $secondvalue = $snewvalue;
					 $thirdvalue = $tnewvalue;
					 $arr_val[] = $arraylists;
					 set_time_limit($php_max_execution_time);
				}while($custom_field_values = $adb->fetch_array($result));

				$sHTML = '<tr>'.$header.'</tr>'.$valtemplate;
				$return_data[] = $sHTML;
				$return_data[] = $noofrows;
				return $return_data;
			}
		}elseif($outputformat == "PRINT_TOTAL")
		{
			$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
			$sSQL = $this->sGetSQLforReport($this->reportid,$filtersql,"COLUMNSTOTOTAL");
			if(isset($this->totallist))
			{
				if($sSQL != "")
				{
					$result = $adb->query($sSQL);
					$y=$adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);

					$coltotalhtml .= "<br /><table align='center' width='60%' cellpadding='3' cellspacing='0' border='1' class='printReport'><tr><td class='rptCellLabel'>".$mod_strings['Totals']."</td><td><b>".$mod_strings['SUM']."</b></td><td><b>".$mod_strings['AVG']."</b></td><td><b>".$mod_strings['MIN']."</b></td><td><b>".$mod_strings['MAX']."</b></td></tr>";

					// Performation Optimization: If Direct output is desired
					if($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END

					foreach($this->totallist as $key=>$value)
					{
						$fieldlist = explode(":",$key);
						$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from ncrm_field where tablename = ? and columnname=?",array($fieldlist[1],$fieldlist[2]));
						if($adb->num_rows($mod_query)>0){
							$module_name = getTabModuleName($adb->query_result($mod_query,0,'tabid'));
							$fieldlabel = trim(str_replace($escapedchars," ",$fieldlist[3]));
							$fieldlabel = str_replace("_", " ", $fieldlabel);
							if($module_name){
								$field = getTranslatedString($module_name, $module_name)." ".getTranslatedString($fieldlabel,$module_name);
							} else {
								$field = getTranslatedString($fieldlabel);
							}
						}
						$uitype_arr[str_replace($escapedchars," ",$module_name."_".$fieldlist[3])] = $adb->query_result($mod_query,0,"uitype");
						$totclmnflds[str_replace($escapedchars," ",$module_name."_".$fieldlist[3])] = $field;
					}

					for($i =0;$i<$y;$i++)
					{
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];

					}
					foreach($totclmnflds as $key=>$value)
					{
						$coltotalhtml .= '<tr class="rptGrpHead">';
						$col_header = getTranslatedString(trim(str_replace($modules," ",$value)));
						$fld_name_1 = $this->primarymodule . "_" . trim($value);
						$fld_name_2 = $this->secondarymodule . "_" . trim($value);
						if($uitype_arr[$key]==71 || $uitype_arr[$key] == 72 ||
										in_array($fld_name_1,$this->append_currency_symbol_to_value) || in_array($fld_name_2,$this->append_currency_symbol_to_value)) {
							$col_header .= " (".$app_strings['LBL_IN']." ".$current_user->currency_symbol.")";
							$convert_price = true;
						} else{
							$convert_price = false;
						}
						$coltotalhtml .= '<td class="rptData">'. $col_header .'</td>';
						$value = trim($key);
						$arraykey = $value.'_SUM';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= "<td class='rptTotal'>".$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$arraykey = $value.'_AVG';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= "<td class='rptTotal'>".$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$arraykey = $value.'_MIN';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= "<td class='rptTotal'>".$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$arraykey = $value.'_MAX';
						if(isset($keyhdr[$arraykey]))
						{
							if($convert_price)
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat ($keyhdr[$arraykey], null, true);
							$coltotalhtml .= "<td class='rptTotal'>".$conv_value.'</td>';
						}else
						{
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$coltotalhtml .= '</tr>';

						// Performation Optimization: If Direct output is desired
						if($directOutput) {
							echo $coltotalhtml;
							$coltotalhtml = '';
						}
						// END
					}

					$coltotalhtml .= "</table>";
					// Performation Optimization: If Direct output is desired
					if($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END
				}
			}
			return $coltotalhtml;
		}
	}

	//<<<<<<<new>>>>>>>>>>
	function getColumnsTotal($reportid)
	{
		// Have we initialized it already?
		if($this->_columnstotallist !== false) {
			return $this->_columnstotallist;
		}

		global $adb;
		global $modules;
		global $log, $current_user;

		static $modulename_cache = array();

		$query = "select * from ncrm_reportmodules where reportmodulesid =?";
		$res = $adb->pquery($query , array($reportid));
		$modrow = $adb->fetch_array($res);
		$premod = $modrow["primarymodule"];
		$secmod = $modrow["secondarymodules"];
		$coltotalsql = "select ncrm_reportsummary.* from ncrm_report";
		$coltotalsql .= " inner join ncrm_reportsummary on ncrm_report.reportid = ncrm_reportsummary.reportsummaryid";
		$coltotalsql .= " where ncrm_report.reportid =?";

		$result = $adb->pquery($coltotalsql, array($reportid));

		while($coltotalrow = $adb->fetch_array($result))
		{
			$fieldcolname = $coltotalrow["columnname"];
			if($fieldcolname != "none")
			{
				$fieldlist = explode(":",$fieldcolname);
				$field_tablename = $fieldlist[1];
				$field_columnname = $fieldlist[2];

				$cachekey = $field_tablename . ":" . $field_columnname;
				if (!isset($modulename_cache[$cachekey])) {
					$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid from ncrm_field where tablename = ? and columnname=?",array($fieldlist[1],$fieldlist[2]));
					if($adb->num_rows($mod_query)>0){
						$module_name = getTabModuleName($adb->query_result($mod_query,0,'tabid'));
						$modulename_cache[$cachekey] = $module_name;
					}
				} else {
					$module_name = $modulename_cache[$cachekey];
				}

				$fieldlabel = trim($fieldlist[3]);
				if($field_tablename == 'ncrm_inventoryproductrel') {
					$field_columnalias = $premod."_".$fieldlist[3];
				} else {
					if($module_name){
						$field_columnalias = $module_name."_".$fieldlist[3];
					} else {
						$field_columnalias = $module_name."_".$fieldlist[3];
					}
				}

				//$field_columnalias = $fieldlist[3];
				$field_permitted = false;
				if(CheckColumnPermission($field_tablename,$field_columnname,$premod) != "false"){
					$field_permitted = true;
				} else {
					$mod = split(":",$secmod);
					foreach($mod as $key){
						if(CheckColumnPermission($field_tablename,$field_columnname,$key) != "false"){
							$field_permitted=true;
						}
					}
				}

				//Calculation fields of "Events" module should show in Calendar related report
				$secondaryModules = split(":", $secmod);
				if ($field_permitted === false && ($premod === 'Calendar' || in_array('Calendar', $secondaryModules)) && CheckColumnPermission($field_tablename, $field_columnname, "Events") != "false") {
					$field_permitted = true;
				}

				if($field_permitted == true)
				{
					$field = $this->getColumnsTotalSQL($fieldlist, $premod);

					if($fieldlist[4] == 2)
					{
						$stdfilterlist[$fieldcolname] = "sum($field) '".$field_columnalias."'";
					}
					if($fieldlist[4] == 3)
					{
						//Fixed average calculation issue due to NULL values ie., when we use avg() function, NULL values will be ignored.to avoid this we use (sum/count) to find average.
						//$stdfilterlist[$fieldcolname] = "avg(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
						$stdfilterlist[$fieldcolname] = "(sum($field)/count(*)) '".$field_columnalias."'";
					}
					if($fieldlist[4] == 4)
					{
						$stdfilterlist[$fieldcolname] = "min($field) '".$field_columnalias."'";
					}
					if($fieldlist[4] == 5)
					{
						$stdfilterlist[$fieldcolname] = "max($field) '".$field_columnalias."'";
					}

					$this->queryPlanner->addTable($field_tablename);
				}
			}
		}
		// Save the information
		$this->_columnstotallist = $stdfilterlist;

		$log->info("ReportRun :: Successfully returned getColumnsTotal".$reportid);
		return $stdfilterlist;
	}
	//<<<<<<new>>>>>>>>>


	function getColumnsTotalSQL($fieldlist, $premod) {
		// Added condition to support detail report calculations
		if($fieldlist[0] == 'cb') {
			$field_tablename = $fieldlist[1];
			$field_columnname = $fieldlist[2];
		} else {
			$field_tablename = $fieldlist[0];
			$field_columnname = $fieldlist[1];
		}

		$field = $field_tablename.".".$field_columnname;
		if($field_tablename == 'ncrm_products' && $field_columnname == 'unit_price') {
			// Query needs to be rebuild to get the value in user preferred currency. [innerProduct and actual_unit_price are table and column alias.]
			$field =  " innerProduct.actual_unit_price";
			$this->queryPlanner->addTable("innerProduct");
		}
		if($field_tablename == 'ncrm_service' && $field_columnname == 'unit_price') {
			// Query needs to be rebuild to get the value in user preferred currency. [innerProduct and actual_unit_price are table and column alias.]
			$field =  " innerService.actual_unit_price";
			$this->queryPlanner->addTable("innerService");

		}
		if(($field_tablename == 'ncrm_invoice' || $field_tablename == 'ncrm_quotes' || $field_tablename == 'ncrm_purchaseorder' || $field_tablename == 'ncrm_salesorder')
				&& ($field_columnname == 'total' || $field_columnname == 'subtotal' || $field_columnname == 'discount_amount' || $field_columnname == 's_h_amount'
						|| $field_columnname == 'paid' || $field_columnname == 'balance' || $field_columnname == 'received')) {
			$field =  " $field_tablename.$field_columnname/$field_tablename.conversion_rate ";
		}

		if($field_tablename == 'ncrm_inventoryproductrel') {
			// Check added so that query planner can prepare query properly for inventory modules
			$this->lineItemFieldsInCalculation = true;
			$field = $field_tablename.$premod.'.'.$field_columnname;
			$itemTableName = 'ncrm_inventoryproductrel'.$premod;
			$this->queryPlanner->addTable($itemTableName);
			$primaryModuleInstance = CRMEntity::getInstance($premod);
			if($field_columnname == 'listprice') {
				$field = $field.'/'.$primaryModuleInstance->table_name.'.conversion_rate';
			} else if($field_columnname == 'discount_amount') {
				$field = ' CASE WHEN '.$itemTableName.'.discount_amount is not null THEN '.$itemTableName.'.discount_amount/'.$primaryModuleInstance->table_name.'.conversion_rate '.
					'WHEN '.$itemTableName.'.discount_percent IS NOT NULL THEN ('.$itemTableName.'.listprice*'.$itemTableName.'.quantity*'.$itemTableName.'.discount_percent/100/'.$primaryModuleInstance->table_name.'.conversion_rate) ELSE 0 END ';
			}
		}
		return $field;
	}

	/** function to get query for the columns to total for the given reportid
	 *  @ param $reportid : Type integer
	 *  This returns columnstoTotal query for the reportid
	 */

	function getColumnsToTotalColumns($reportid)
	{
		global $adb;
		global $modules;
		global $log;

		$sreportstdfiltersql = "select ncrm_reportsummary.* from ncrm_report";
		$sreportstdfiltersql .= " inner join ncrm_reportsummary on ncrm_report.reportid = ncrm_reportsummary.reportsummaryid";
		$sreportstdfiltersql .= " where ncrm_report.reportid =?";

		$result = $adb->pquery($sreportstdfiltersql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");

			if($fieldcolname != "none")
			{
				$fieldlist = explode(":",$fieldcolname);
				if($fieldlist[4] == 2)
				{
					$sSQLList[] = "sum(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
				if($fieldlist[4] == 3)
				{
					$sSQLList[] = "avg(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
				if($fieldlist[4] == 4)
				{
					$sSQLList[] = "min(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
				if($fieldlist[4] == 5)
				{
					$sSQLList[] = "max(".$fieldlist[1].".".$fieldlist[2].") ".$fieldlist[3];
				}
			}
		}
		if(isset($sSQLList))
		{
			$sSQL = implode(",",$sSQLList);
		}
		$log->info("ReportRun :: Successfully returned getColumnsToTotalColumns".$reportid);
		return $sSQL;
	}
	/** Function to convert the Report Header Names into i18n
	 *  @param $fldname: Type Varchar
	 *  Returns Language Converted Header Strings
	 **/
	function getLstringforReportHeaders($fldname)
	{
		global $modules,$current_language,$current_user,$app_strings;
		$rep_header = ltrim($fldname);
		$rep_header = decode_html($rep_header);
		$labelInfo = explode('_', $rep_header);
		$rep_module = $labelInfo[0];
		if(is_array($this->labelMapping) && !empty($this->labelMapping[$rep_header])) {
			$rep_header = $this->labelMapping[$rep_header];
		} else {
			if($rep_module == 'LBL') {
				$rep_module = '';
			}
			array_shift($labelInfo);
			$fieldLabel = decode_html(implode("_",$labelInfo));
			$rep_header_temp = preg_replace("/\s+/","_",$fieldLabel);
			$rep_header = "$rep_module $fieldLabel";
		}
		$curr_symb = "";
		$fieldLabel = ltrim(str_replace($rep_module, '', $rep_header), '_');
		$fieldInfo = getFieldByReportLabel($rep_module, $fieldLabel);
		if($fieldInfo['uitype'] == '71') {
        	$curr_symb = " (".$app_strings['LBL_IN']." ".$current_user->currency_symbol.")";
		}
        $rep_header .=$curr_symb;

		return $rep_header;
	}

	/** Function to get picklist value array based on profile
	 *          *  returns permitted fields in array format
	 **/


	function getAccessPickListValues()
	{
		global $adb;
		global $current_user;
		$id = array(getTabid($this->primarymodule));
		if($this->secondarymodule != '')
			array_push($id,  getTabid($this->secondarymodule));

		$query = 'select fieldname,columnname,fieldid,fieldlabel,tabid,uitype from ncrm_field where tabid in('. generateQuestionMarks($id) .') and uitype in (15,33,55)'; //and columnname in (?)';
		$result = $adb->pquery($query, $id);//,$select_column));
		$roleid=$current_user->roleid;
		$subrole = getRoleSubordinates($roleid);
		if(count($subrole)> 0)
		{
			$roleids = $subrole;
			array_push($roleids, $roleid);
		}
		else
		{
			$roleids = $roleid;
		}

		$temp_status = Array();
		for($i=0;$i < $adb->num_rows($result);$i++)
		{
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$tabid = $adb->query_result($result,$i,"tabid");
			$uitype = $adb->query_result($result,$i,"uitype");

			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$keyvalue = getTabModuleName($tabid)."_".$fieldlabel1;
			$fieldvalues = Array();
			if (count($roleids) > 1) {
				$mulsel="select distinct $fieldname from ncrm_$fieldname inner join ncrm_role2picklist on ncrm_role2picklist.picklistvalueid = ncrm_$fieldname.picklist_valueid where roleid in (\"". implode($roleids,"\",\"") ."\") and picklistid in (select picklistid from ncrm_$fieldname)"; // order by sortid asc - not requried
			} else {
				$mulsel="select distinct $fieldname from ncrm_$fieldname inner join ncrm_role2picklist on ncrm_role2picklist.picklistvalueid = ncrm_$fieldname.picklist_valueid where roleid ='".$roleid."' and picklistid in (select picklistid from ncrm_$fieldname)"; // order by sortid asc - not requried
			}
			if($fieldname != 'firstname')
				$mulselresult = $adb->query($mulsel);
			for($j=0;$j < $adb->num_rows($mulselresult);$j++)
			{
				$fldvalue = $adb->query_result($mulselresult,$j,$fieldname);
				if(in_array($fldvalue,$fieldvalues)) continue;
				$fieldvalues[] = $fldvalue;
			}
			$field_count = count($fieldvalues);
			if( $uitype == 15 && $field_count > 0 && ($fieldname == 'taskstatus' || $fieldname == 'eventstatus'))
			{
				$temp_count =count($temp_status[$keyvalue]);
				if($temp_count > 0)
				{
					for($t=0;$t < $field_count;$t++)
					{
						$temp_status[$keyvalue][($temp_count+$t)] = $fieldvalues[$t];
					}
					$fieldvalues = $temp_status[$keyvalue];
				}
				else
					$temp_status[$keyvalue] = $fieldvalues;
			}

			if($uitype == 33)
				$fieldlists[1][$keyvalue] = $fieldvalues;
			else if($uitype == 55 && $fieldname == 'salutationtype')
				$fieldlists[$keyvalue] = $fieldvalues;
	        else if($uitype == 15)
		        $fieldlists[$keyvalue] = $fieldvalues;
		}
		return $fieldlists;
	}

	function getReportPDF($filterlist=false) {
		require_once 'libraries/tcpdf/tcpdf.php';

		$reportData = $this->GenerateReport("PDF",$filterlist);
        $arr_val = $reportData['data'];

		if(isset($arr_val)) {
			foreach($arr_val as $wkey=>$warray_value) {
				foreach($warray_value as $whd=>$wvalue) {
					if(strlen($wvalue) < strlen($whd)) {
						$w_inner_array[] = strlen($whd);
					} else {
						$w_inner_array[] = strlen($wvalue);
					}
				}
				$warr_val[] = $w_inner_array;
				unset($w_inner_array);
			}

			foreach($warr_val[0] as $fkey=>$fvalue) {
				foreach($warr_val as $wkey=>$wvalue) {
					$f_inner_array[] = $warr_val[$wkey][$fkey];
				}
				sort($f_inner_array,1);
				$farr_val[] = $f_inner_array;
				unset($f_inner_array);
			}

			foreach($farr_val as $skkey=>$skvalue) {
				if($skvalue[count($arr_val)-1] == 1) {
					$col_width[] = ($skvalue[count($arr_val)-1] * 50);
				} else {
					$col_width[] = ($skvalue[count($arr_val)-1] * 10) + 10 ;
				}
			}
			$count = 0;
			foreach($arr_val[0] as $key=>$value) {
				$headerHTML .= '<td width="'.$col_width[$count].'" bgcolor="#DDDDDD"><b>'.$this->getLstringforReportHeaders($key).'</b></td>';
				$count = $count + 1;
			}

			foreach($arr_val as $key=>$array_value) {
				$valueHTML = "";
				$count = 0;
				foreach($array_value as $hd=>$value) {
					$valueHTML .= '<td width="'.$col_width[$count].'">'.$value.'</td>';
					$count = $count + 1;
				}
				$dataHTML .= '<tr>'.$valueHTML.'</tr>';
			}

		}

		$totalpdf = $this->GenerateReport("PRINT_TOTAL",$filterlist);
		$html = '<table border="0.5"><tr>'.$headerHTML.'</tr>'.$dataHTML.'<tr><td>'.$totalpdf.'</td></tr>'.'</table>';
		$columnlength = array_sum($col_width);
		if($columnlength > 14400) {
			die("<br><br><center>".$app_strings['LBL_PDF']." <a href='javascript:window.history.back()'>".$app_strings['LBL_GO_BACK'].".</a></center>");
		}
		if($columnlength <= 420 ) {
			$pdf = new TCPDF('P','mm','A5',true);

		} elseif($columnlength >= 421 && $columnlength <= 1120) {
			$pdf = new TCPDF('L','mm','A3',true);

		}elseif($columnlength >=1121 && $columnlength <= 1600) {
			$pdf = new TCPDF('L','mm','A2',true);

		}elseif($columnlength >=1601 && $columnlength <= 2200) {
			$pdf = new TCPDF('L','mm','A1',true);
		}
		elseif($columnlength >=2201 && $columnlength <= 3370) {
			$pdf = new TCPDF('L','mm','A0',true);
		}
		elseif($columnlength >=3371 && $columnlength <= 4690) {
			$pdf = new TCPDF('L','mm','2A0',true);
		}
		elseif($columnlength >=4691 && $columnlength <= 6490) {
			$pdf = new TCPDF('L','mm','4A0',true);
		}
		else {
			$columnhight = count($arr_val)*15;
			$format = array($columnhight,$columnlength);
			$pdf = new TCPDF('L','mm',$format,true);
		}
		$pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setLanguageArray($l);
		$pdf->AddPage();

		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('FreeSerif','B',14);
		$pdf->Cell(($pdf->columnlength*50),10,getTranslatedString($oReport->reportname),0,0,'C',0);
		//$pdf->writeHTML($oReport->reportname);
		$pdf->Ln();

		$pdf->SetFont('FreeSerif','',10);

		$pdf->writeHTML($html);

		return $pdf;
	}

	function writeReportToExcelFile($fileName, $filterlist='') {

		global $currentModule, $current_language;
		$mod_strings = return_module_language($current_language, $currentModule);

		require_once("libraries/PHPExcel/PHPExcel.php");

		$workbook = new PHPExcel();
		$worksheet = $workbook->setActiveSheetIndex(0);

		$reportData = $this->GenerateReport("PDF",$filterlist);
        $arr_val = $reportData['data'];
		$totalxls = $this->GenerateReport("TOTALXLS",$filterlist);

		$header_styles = array(
			'fill' => array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb'=>'E1E0F7') ),
			//'font' => array( 'bold' => true )
		);

		if(isset($arr_val)) {
			$count = 0;
			$rowcount = 1;
            //copy the first value details
            $arrayFirstRowValues = $arr_val[0];
			array_pop($arrayFirstRowValues);			// removed action link in details
			foreach($arrayFirstRowValues as $key=>$value) {
				$worksheet->setCellValueExplicitByColumnAndRow($count, $rowcount, $key, true);
				$worksheet->getStyleByColumnAndRow($count, $rowcount)->applyFromArray($header_styles);

				// NOTE Performance overhead: http://stackoverflow.com/questions/9965476/phpexcel-column-size-issues
				//$worksheet->getColumnDimensionByColumn($count)->setAutoSize(true);

				$count = $count + 1;
			}

			$rowcount++;
			foreach($arr_val as $key=>$array_value) {
				$count = 0;
				array_pop($array_value);	// removed action link in details
				foreach($array_value as $hdr=>$value) {
					if($hdr == 'ACTION') continue;
					$value = decode_html($value);
					// TODO Determine data-type based on field-type.
					// String type helps having numbers prefixed with 0 intact.
					$worksheet->setCellValueExplicitByColumnAndRow($count, $rowcount, $value, PHPExcel_Cell_DataType::TYPE_STRING);
					$count = $count + 1;
				}
				$rowcount++;
			}

			// Summary Total
			$rowcount++;
			$count=0;
			if(is_array($totalxls[0])) {
				foreach($totalxls[0] as $key=>$value) {
					$chdr=substr($key,-3,3);
					$translated_str = in_array($chdr ,array_keys($mod_strings))?$mod_strings[$chdr]:$key;
					$worksheet->setCellValueExplicitByColumnAndRow($count, $rowcount, $translated_str);

					$worksheet->getStyleByColumnAndRow($count, $rowcount)->applyFromArray($header_styles);

					$count = $count + 1;
				}
			}

			$rowcount++;
			foreach($totalxls as $key=>$array_value) {
				$count = 0;
				foreach($array_value as $hdr=>$value) {
					$value = decode_html($value);
					$worksheet->setCellValueExplicitByColumnAndRow($count, $key+$rowcount, $value);
					$count = $count + 1;
				}
			}
		}

		$workbookWriter = PHPExcel_IOFactory::createWriter($workbook, 'Excel5');
		$workbookWriter->save($fileName);
	}

	function writeReportToCSVFile($fileName, $filterlist='') {

		global $currentModule, $current_language;
		$mod_strings = return_module_language($current_language, $currentModule);

		$reportData = $this->GenerateReport("PDF",$filterlist);
        $arr_val = $reportData['data'];

		$fp = fopen($fileName, 'w+');
		fputs($fp,chr(239) . chr(187) . chr(191));//UTF-8 byte order mark
		if(isset($arr_val)) {
			$csv_values = array();
			// Header
			$csv_values = array_keys($arr_val[0]);
			array_pop($csv_values);			//removed header in csv file
			fputcsv($fp, $csv_values);
			foreach($arr_val as $key=>$array_value) {
				array_pop($array_value);	//removed action link
				$csv_values = array_map('decode_html', array_values($array_value));
				fputcsv($fp, $csv_values);
			}
		}
		fclose($fp);
	}

    function getGroupByTimeList($reportId){
        global $adb;
        $groupByTimeQuery = "SELECT * FROM ncrm_reportgroupbycolumn WHERE reportid=?";
        $groupByTimeRes = $adb->pquery($groupByTimeQuery,array($reportId));
        $num_rows = $adb->num_rows($groupByTimeRes);
        for($i=0;$i<$num_rows;$i++){
            $sortColName = $adb->query_result($groupByTimeRes, $i,'sortcolname');
            list($tablename,$colname,$module_field,$fieldname,$single) = split(':',$sortColName);
            $groupField = $module_field;
            $groupCriteria = $adb->query_result($groupByTimeRes, $i,'dategroupbycriteria');
            if(in_array($groupCriteria,array_keys($this->groupByTimeParent))){
                $parentCriteria = $this->groupByTimeParent[$groupCriteria];
                foreach($parentCriteria as $criteria){
                  $groupByCondition[]=$this->GetTimeCriteriaCondition($criteria, $groupField);
                }
            }
            $groupByCondition[] = $this->GetTimeCriteriaCondition($groupCriteria, $groupField);
			$this->queryPlanner->addTable($tablename);
        }
        return $groupByCondition;
    }

    function GetTimeCriteriaCondition($criteria,$dateField){
        $condition = "";
        if(strtolower($criteria)=='year'){
            $condition = "DATE_FORMAT($dateField, '%Y' )";
        }
        else if (strtolower($criteria)=='month'){
            $condition = "CEIL(DATE_FORMAT($dateField,'%m')%13)";
        }
        else if(strtolower($criteria)=='quarter'){
            $condition = "CEIL(DATE_FORMAT($dateField,'%m')/3)";
        }
        return $condition;
    }

    function GetFirstSortByField($reportid)
    {
        global $adb;
        $groupByField ="";
        $sortFieldQuery = "SELECT * FROM ncrm_reportsortcol
                            LEFT JOIN ncrm_reportgroupbycolumn ON (ncrm_reportsortcol.sortcolid = ncrm_reportgroupbycolumn.sortid and ncrm_reportsortcol.reportid = ncrm_reportgroupbycolumn.reportid)
                            WHERE columnname!='none' and ncrm_reportsortcol.reportid=? ORDER By sortcolid";
        $sortFieldResult= $adb->pquery($sortFieldQuery,array($reportid));
		$inventoryModules = getInventoryModules();
        if($adb->num_rows($sortFieldResult)>0){
            $fieldcolname = $adb->query_result($sortFieldResult,0,'columnname');
            list($tablename,$colname,$module_field,$fieldname,$typeOfData) = explode(":",$fieldcolname);
			list($modulename,$fieldlabel) = explode('_', $module_field, 2);
            $groupByField = $module_field;
            if($typeOfData == "D"){
                $groupCriteria = $adb->query_result($sortFieldResult,0,'dategroupbycriteria');
                if(strtolower($groupCriteria)!='none'){
                    if(in_array($groupCriteria,array_keys($this->groupByTimeParent))){
                        $parentCriteria = $this->groupByTimeParent[$groupCriteria];
                        foreach($parentCriteria as $criteria){
                          $groupByCondition[]=$this->GetTimeCriteriaCondition($criteria, $groupByField);
                        }
                    }
                    $groupByCondition[] = $this->GetTimeCriteriaCondition($groupCriteria, $groupByField);
                    $groupByField = implode(", ",$groupByCondition);
                }

            } elseif(CheckFieldPermission($fieldname,$modulename) != 'true') {
				if (!(in_array($modulename, $inventoryModules) && $fieldname == 'serviceid')) {
					$groupByField = $tablename.".".$colname;
				}
			}
        }
        return $groupByField;
    }

	function getReferenceFieldColumnList($moduleName, $fieldInfo) {
		$adb = PearDatabase::getInstance();

		$columnsSqlList = array();

		$fieldInstance = WebserviceField::fromArray($adb, $fieldInfo);
		$referenceModuleList = $fieldInstance->getReferenceList();
		$reportSecondaryModules = explode(':', $this->secondarymodule);

		if($moduleName != $this->primarymodule && in_array($this->primarymodule, $referenceModuleList)) {
			$entityTableFieldNames = getEntityFieldNames($this->primarymodule);
			$entityTableName = $entityTableFieldNames['tablename'];
			$entityFieldNames = $entityTableFieldNames['fieldname'];

			$columnList = array();
			if(is_array($entityFieldNames)) {
				foreach ($entityFieldNames as $entityColumnName) {
					$columnList["$entityColumnName"] = "$entityTableName.$entityColumnName";
				}
			} else {
				$columnList[] = "$entityTableName.$entityFieldNames";
			}
			if(count($columnList) > 1) {
				$columnSql = getSqlForNameInDisplayFormat($columnList, $this->primarymodule);
			} else {
				$columnSql = implode('', $columnList);
			}
			$columnsSqlList[] = $columnSql;

		} else {
			foreach($referenceModuleList as $referenceModule) {
				$entityTableFieldNames = getEntityFieldNames($referenceModule);
				$entityTableName = $entityTableFieldNames['tablename'];
				$entityFieldNames = $entityTableFieldNames['fieldname'];

				$referenceTableName = '';
				$dependentTableName = '';

				if($moduleName == 'HelpDesk' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountRelHelpDesk';
				} elseif ($moduleName == 'HelpDesk' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsRelHelpDesk';
				} elseif ($moduleName == 'HelpDesk' && $referenceModule == 'Products') {
					$referenceTableName = 'ncrm_productsRel';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Leads') {
					$referenceTableName = 'ncrm_leaddetailsRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Potentials') {
					$referenceTableName = 'ncrm_potentialRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Invoice') {
					$referenceTableName = 'ncrm_invoiceRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Quotes') {
					$referenceTableName = 'ncrm_quotesRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'PurchaseOrder') {
					$referenceTableName = 'ncrm_purchaseorderRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'SalesOrder') {
					$referenceTableName = 'ncrm_salesorderRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'HelpDesk') {
					$referenceTableName = 'ncrm_troubleticketsRelCalendar';
				} elseif ($moduleName == 'Calendar' && $referenceModule == 'Campaigns') {
					$referenceTableName = 'ncrm_campaignRelCalendar';
				} elseif ($moduleName == 'Contacts' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountContacts';
				} elseif ($moduleName == 'Contacts' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsContacts';
				} elseif ($moduleName == 'Accounts' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountAccounts';
				} elseif ($moduleName == 'Campaigns' && $referenceModule == 'Products') {
					$referenceTableName = 'ncrm_productsCampaigns';
				} elseif ($moduleName == 'Faq' && $referenceModule == 'Products') {
					$referenceTableName = 'ncrm_productsFaq';
				} elseif ($moduleName == 'Invoice' && $referenceModule == 'SalesOrder') {
					$referenceTableName = 'ncrm_salesorderInvoice';
				} elseif ($moduleName == 'Invoice' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsInvoice';
				} elseif ($moduleName == 'Invoice' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountInvoice';
				} elseif ($moduleName == 'Potentials' && $referenceModule == 'Campaigns') {
					$referenceTableName = 'ncrm_campaignPotentials';
				} elseif ($moduleName == 'Products' && $referenceModule == 'Vendors') {
					$referenceTableName = 'ncrm_vendorRelProducts';
				} elseif ($moduleName == 'PurchaseOrder' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsPurchaseOrder';
				} elseif ($moduleName == 'PurchaseOrder' && $referenceModule == 'Vendors') {
					$referenceTableName = 'ncrm_vendorRelPurchaseOrder';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Potentials') {
					$referenceTableName = 'ncrm_potentialRelQuotes';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountQuotes';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsQuotes';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Potentials') {
					$referenceTableName = 'ncrm_potentialRelSalesOrder';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountSalesOrder';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsSalesOrder';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Quotes') {
					$referenceTableName = 'ncrm_quotesSalesOrder';
				} elseif ($moduleName == 'Potentials' && $referenceModule == 'Contacts') {
					$referenceTableName = 'ncrm_contactdetailsPotentials';
				} elseif ($moduleName == 'Potentials' && $referenceModule == 'Accounts') {
					$referenceTableName = 'ncrm_accountPotentials';
				} elseif ($moduleName == 'ModComments' && $referenceModule == 'Users') {
					$referenceTableName = 'ncrm_usersModComments';
				} elseif (in_array($referenceModule, $reportSecondaryModules)) {
					$referenceTableName = "{$entityTableName}Rel$referenceModule";
					$dependentTableName = "ncrm_crmentityRel{$referenceModule}{$fieldInstance->getFieldId()}";
				} elseif (in_array($moduleName, $reportSecondaryModules)) {
					$referenceTableName = "{$entityTableName}Rel$moduleName";
					$dependentTableName = "ncrm_crmentityRel{$moduleName}{$fieldInstance->getFieldId()}";
				} else {
					$referenceTableName = "{$entityTableName}Rel{$moduleName}{$fieldInstance->getFieldId()}";
					$dependentTableName = "ncrm_crmentityRel{$moduleName}{$fieldInstance->getFieldId()}";
				}

				$this->queryPlanner->addTable($referenceTableName);

				if(isset($dependentTableName)){
				    $this->queryPlanner->addTable($dependentTableName);
				}
				$columnList = array();
				if(is_array($entityFieldNames)) {
					foreach ($entityFieldNames as $entityColumnName) {
						$columnList["$entityColumnName"] = "$referenceTableName.$entityColumnName";
					}
				} else {
					$columnList[] = "$referenceTableName.$entityFieldNames";
				}
				if(count($columnList) > 1) {
					$columnSql = getSqlForNameInDisplayFormat($columnList, $referenceModule);
				} else {
					$columnSql = implode('', $columnList);
				}
				if ($referenceModule == 'DocumentFolders' && $fieldInstance->getFieldName() == 'folderid') {
					$columnSql = 'ncrm_attachmentsfolder.foldername';
					$this->queryPlanner->addTable("ncrm_attachmentsfolder");
				}
				if ($referenceModule == 'Currency' && $fieldInstance->getFieldName() == 'currency_id') {
					$columnSql = "ncrm_currency_info$moduleName.currency_name";
					$this->queryPlanner->addTable("ncrm_currency_info$moduleName");
				    }
				$columnsSqlList[] = "trim($columnSql)";
			}
		}
		return $columnsSqlList;
	}
}
?>
