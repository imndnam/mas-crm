<?php
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
*
 ********************************************************************************/

class PriceBooks extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "ncrm_pricebook";
	var $table_index= 'pricebookid';
	var $tab_name = Array('ncrm_crmentity','ncrm_pricebook','ncrm_pricebookcf');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_pricebook'=>'pricebookid','ncrm_pricebookcf'=>'pricebookid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_pricebookcf', 'pricebookid');
	var $column_fields = Array();

	var $sortby_fields = Array('bookname');

        // This is the list of fields that are in the lists.
	var $list_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname'),
                                'Active'=>Array('pricebook'=>'active')
                                );

	var $list_fields_name = Array(
                                        'Price Book Name'=>'bookname',
                                        'Active'=>'active'
                                     );
	var $list_link_field= 'bookname';

	var $search_fields = Array(
                                'Price Book Name'=>Array('pricebook'=>'bookname')
                                );
	var $search_fields_name = Array(
                                        'Price Book Name'=>'bookname'
                                     );

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'bookname';
	var $default_sort_order = 'ASC';

	var $mandatory_fields = Array('bookname','currency_id','pricebook_no','createdtime' ,'modifiedtime');

	// For Alphabetical search
	var $def_basicsearch_col = 'bookname';

	/**	Constructor which will set the column_fields in this object
	 */
	function PriceBooks() {
		$this->log =LoggerManager::getLogger('pricebook');
		$this->log->debug("Entering PriceBooks() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('PriceBooks');
		$this->log->debug("Exiting PriceBook method ...");
	}

	function save_module($module)
	{
		// Update the list prices in the price book with the unit price, if the Currency has been changed
		$this->updateListPrices();
	}

	/* Function to Update the List prices for all the products of a current price book
	   with its Unit price, if the Currency for Price book has changed. */
	function updateListPrices() {
		global $log, $adb;
		$log->debug("Entering function updateListPrices...");
		$pricebook_currency = $this->column_fields['currency_id'];
		$prod_res = $adb->pquery("select * from ncrm_pricebookproductrel where pricebookid=? AND usedcurrency != ?",
							array($this->id, $pricebook_currency));
		$numRows = $adb->num_rows($prod_res);

		for($i=0;$i<$numRows;$i++) {
			$product_id = $adb->query_result($prod_res,$i,'productid');
			$list_price = $adb->query_result($prod_res,$i,'listprice');
			$used_currency = $adb->query_result($prod_res,$i,'usedcurrency');
			$product_currency_info = getCurrencySymbolandCRate($used_currency);
			$product_conv_rate = $product_currency_info['rate'];
			$pricebook_currency_info = getCurrencySymbolandCRate($pricebook_currency);
			$pb_conv_rate = $pricebook_currency_info['rate'];
			$conversion_rate = $pb_conv_rate / $product_conv_rate;
			$computed_list_price = $list_price * $conversion_rate;

			$query = "update ncrm_pricebookproductrel set listprice=?, usedcurrency=? where pricebookid=? and productid=?";
			$params = array($computed_list_price, $pricebook_currency, $this->id, $product_id);
			$adb->pquery($query, $params);
		}
		$log->debug("Exiting function updateListPrices...");
	}

	/**	function used to get the products which are related to the pricebook
	 *	@param int $id - pricebook id
         *      @return array - return an array which will be returned from the function getPriceBookRelatedProducts
        **/
	function get_pricebook_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_pricebook_products(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='submit' name='button' onclick=\"this.form.action.value='AddProductsToPriceBook';this.form.module.value='$related_module';this.form.return_module.value='$currentModule';this.form.return_action.value='PriceBookDetailView'\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}

		$query = 'SELECT ncrm_products.productid, ncrm_products.productname, ncrm_products.productcode, ncrm_products.commissionrate,
						ncrm_products.qty_per_unit, ncrm_products.unit_price, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
						ncrm_pricebookproductrel.listprice
				FROM ncrm_products
				INNER JOIN ncrm_pricebookproductrel ON ncrm_products.productid = ncrm_pricebookproductrel.productid
				INNER JOIN ncrm_crmentity on ncrm_crmentity.crmid = ncrm_products.productid
				INNER JOIN ncrm_pricebook on ncrm_pricebook.pricebookid = ncrm_pricebookproductrel.pricebookid
				LEFT JOIN ncrm_users ON ncrm_users.id=ncrm_crmentity.smownerid
				LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid '
				. getNonAdminAccessControlQuery($related_module, $current_user) .'
				WHERE ncrm_pricebook.pricebookid = '.$id.' and ncrm_crmentity.deleted = 0';

		$this->retrieve_entity_info($id,$this_module);
		$return_value = getPriceBookRelatedProducts($query,$this,$returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_pricebook_products method ...");
		return $return_value;
	}

	/**	function used to get the services which are related to the pricebook
	 *	@param int $id - pricebook id
         *      @return array - return an array which will be returned from the function getPriceBookRelatedServices
        **/
	function get_pricebook_services($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_pricebook_services(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='submit' name='button' onclick=\"this.form.action.value='AddServicesToPriceBook';this.form.module.value='$related_module';this.form.return_module.value='$currentModule';this.form.return_action.value='PriceBookDetailView'\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}

		$query = 'SELECT ncrm_service.serviceid, ncrm_service.servicename, ncrm_service.commissionrate,
					ncrm_service.qty_per_unit, ncrm_service.unit_price, ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
					ncrm_pricebookproductrel.listprice
			FROM ncrm_service
			INNER JOIN ncrm_pricebookproductrel on ncrm_service.serviceid = ncrm_pricebookproductrel.productid
			INNER JOIN ncrm_crmentity on ncrm_crmentity.crmid = ncrm_service.serviceid
			INNER JOIN ncrm_pricebook on ncrm_pricebook.pricebookid = ncrm_pricebookproductrel.pricebookid
			LEFT JOIN ncrm_users ON ncrm_users.id=ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid '
			. getNonAdminAccessControlQuery($related_module, $current_user) .'
			WHERE ncrm_pricebook.pricebookid = '.$id.' and ncrm_crmentity.deleted = 0';

		$this->retrieve_entity_info($id,$this_module);
		$return_value = $other->getPriceBookRelatedServices($query,$this,$returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_pricebook_services method ...");
		return $return_value;
	}

	/**	function used to get whether the pricebook has related with a product or not
	 *	@param int $id - product id
	 *	@return true or false - if there are no pricebooks available or associated pricebooks for the product is equal to total number of pricebooks then return false, else return true
	 */
	function get_pricebook_noproduct($id)
	{
		global $log;
		$log->debug("Entering get_pricebook_noproduct(".$id.") method ...");

		$query = "select ncrm_crmentity.crmid, ncrm_pricebook.* from ncrm_pricebook inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_pricebook.pricebookid where ncrm_crmentity.deleted=0";
		$result = $this->db->pquery($query, array());
		$no_count = $this->db->num_rows($result);
		if($no_count !=0)
		{
       	 	$pb_query = 'select ncrm_crmentity.crmid, ncrm_pricebook.pricebookid,ncrm_pricebookproductrel.productid from ncrm_pricebook inner join ncrm_crmentity on ncrm_crmentity.crmid=ncrm_pricebook.pricebookid inner join ncrm_pricebookproductrel on ncrm_pricebookproductrel.pricebookid=ncrm_pricebook.pricebookid where ncrm_crmentity.deleted=0 and ncrm_pricebookproductrel.productid=?';
			$result_pb = $this->db->pquery($pb_query, array($id));
			if($no_count == $this->db->num_rows($result_pb))
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return false;
			}
			elseif($this->db->num_rows($result_pb) == 0)
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
			elseif($this->db->num_rows($result_pb) < $no_count)
			{
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
		}
		else
		{
			$log->debug("Exiting get_pricebook_noproduct method ...");
			return false;
		}
	}

	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module,$queryplanner){
	 			$moduletable = $this->table_name;
	 			$moduleindex = $this->table_index;
				$modulecftable = $this->customFieldTable[0];
				$modulecfindex = $this->customFieldTable[1];

				$cfquery = '';
				if(isset($modulecftable) && $queryplanner->requireTable($modulecftable) ){
					$cfquery = "inner join $modulecftable as $modulecftable on $modulecftable.$modulecfindex=$moduletable.$moduleindex";
				}

	 			$query = "from $moduletable $cfquery
					inner join ncrm_crmentity on ncrm_crmentity.crmid=$moduletable.$moduleindex";
				if ($queryplanner->requireTable("ncrm_currency_info$module")){
				    $query .= "  left join ncrm_currency_info as ncrm_currency_info$module on ncrm_currency_info$module.id = $moduletable.currency_id";
				}
				if ($queryplanner->requireTable("ncrm_groups$module")){
				    $query .= " left join ncrm_groups as ncrm_groups$module on ncrm_groups$module.groupid = ncrm_crmentity.smownerid";
				}
				if ($queryplanner->requireTable("ncrm_users$module")){
				    $query .= " left join ncrm_users as ncrm_users$module on ncrm_users$module.id = ncrm_crmentity.smownerid";
				}
				$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
				$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";

				if ($queryplanner->requireTable("ncrm_lastModifiedByPriceBooks")){
				    $query .= " left join ncrm_users as ncrm_lastModifiedByPriceBooks on ncrm_lastModifiedByPriceBooks.id = ncrm_crmentity.modifiedby ";
				}
				return $query;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner) {

		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("ncrm_crmentityPriceBooks",array("ncrm_usersPriceBooks","ncrm_groupsPriceBooks"));
		$matrix->setDependency("ncrm_pricebook",array("ncrm_crmentityPriceBooks","ncrm_currency_infoPriceBooks"));
		if (!$queryplanner->requireTable('ncrm_pricebook', $matrix)) {
			return '';
		}

		$query = $this->getRelationQuery($module,$secmodule,"ncrm_pricebook","pricebookid", $queryplanner);
		// TODO Support query planner
		if ($queryplanner->requireTable("ncrm_crmentityPriceBooks",$matrix)){
		$query .=" left join ncrm_crmentity as ncrm_crmentityPriceBooks on ncrm_crmentityPriceBooks.crmid=ncrm_pricebook.pricebookid and ncrm_crmentityPriceBooks.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_currency_infoPriceBooks")){
		$query .=" left join ncrm_currency_info as ncrm_currency_infoPriceBooks on ncrm_currency_infoPriceBooks.id = ncrm_pricebook.currency_id";
		}
		if ($queryplanner->requireTable("ncrm_usersPriceBooks")){
		    $query .=" left join ncrm_users as ncrm_usersPriceBooks on ncrm_usersPriceBooks.id = ncrm_crmentityPriceBooks.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_groupsPriceBooks")){
		    $query .=" left join ncrm_groups as ncrm_groupsPriceBooks on ncrm_groupsPriceBooks.groupid = ncrm_crmentityPriceBooks.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_lastModifiedByPriceBooks")){
		    $query .=" left join ncrm_users as ncrm_lastModifiedByPriceBooks on ncrm_lastModifiedByPriceBooks.id = ncrm_crmentityPriceBooks.smownerid";
		}
        if ($queryplanner->requireTable("ncrm_createdbyPriceBooks")){
			$query .= " left join ncrm_users as ncrm_createdbyPriceBooks on ncrm_createdbyPriceBooks.id = ncrm_crmentityPriceBooks.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Products" => array("ncrm_pricebookproductrel"=>array("pricebookid","productid"),"ncrm_pricebook"=>"pricebookid"),
			"Services" => array("ncrm_pricebookproductrel"=>array("pricebookid","productid"),"ncrm_pricebook"=>"pricebookid"),
		);
		return $rel_tables[$secmodule];
	}

}
?>
