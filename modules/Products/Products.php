<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class Products extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name = 'ncrm_products';
	var $table_index= 'productid';
    var $column_fields = Array();

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_productcf','productid');

	var $tab_name = Array('ncrm_crmentity','ncrm_products','ncrm_productcf');

	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_products'=>'productid','ncrm_productcf'=>'productid','ncrm_seproductsrel'=>'productid','ncrm_producttaxrel'=>'productid');



	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
		'Product Name'=>Array('products'=>'productname'),
		'Part Number'=>Array('products'=>'productcode'),
		'Commission Rate'=>Array('products'=>'commissionrate'),
		'Qty/Unit'=>Array('products'=>'qty_per_unit'),
		'Unit Price'=>Array('products'=>'unit_price')
	);
	var $list_fields_name = Array(
		'Product Name'=>'productname',
		'Part Number'=>'productcode',
		'Commission Rate'=>'commissionrate',
		'Qty/Unit'=>'qty_per_unit',
		'Unit Price'=>'unit_price'
	);

	var $list_link_field= 'productname';

	var $search_fields = Array(
		'Product Name'=>Array('products'=>'productname'),
		'Part Number'=>Array('products'=>'productcode'),
		'Unit Price'=>Array('products'=>'unit_price')
	);
	var $search_fields_name = Array(
		'Product Name'=>'productname',
		'Part Number'=>'productcode',
		'Unit Price'=>'unit_price'
	);

    var $required_fields = Array(
            'productname'=>1
    );

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();
	var $def_basicsearch_col = 'productname';

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'productname';
	var $default_sort_order = 'ASC';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to ncrm_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'productname', 'assigned_user_id');
	 // Josh added for importing and exporting -added in patch2
    var $unit_price;

	/**	Constructor which will set the column_fields in this object
	 */
	function Products() {
		$this->log =LoggerManager::getLogger('product');
		$this->log->debug("Entering Products() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Products');
		$this->log->debug("Exiting Product method ...");
	}

	function save_module($module)
	{
		//Inserting into product_taxrel table
		if($_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates')
		{
			$this->insertTaxInformation('ncrm_producttaxrel', 'Products');
			$this->insertPriceInformation('ncrm_productcurrencyrel', 'Products');
		}

		// Update unit price value in ncrm_productcurrencyrel
		$this->updateUnitPrice();
		//Inserting into attachments
		$this->insertIntoAttachment($this->id,'Products');

	}

	/**	function to save the product tax information in ncrm_producttaxrel table
	 *	@param string $tablename - ncrm_tablename to save the product tax relationship (producttaxrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertTaxInformation($tablename, $module)
	{
		global $adb, $log;
		$log->debug("Entering into insertTaxInformation($tablename, $module) method ...");
		$tax_details = getAllTaxes();

		$tax_per = '';
		//Save the Product - tax relationship if corresponding tax check box is enabled
		//Delete the existing tax if any
		if($this->mode == 'edit')
		{
			for($i=0;$i<count($tax_details);$i++)
			{
				$taxid = getTaxId($tax_details[$i]['taxname']);
				$sql = "delete from ncrm_producttaxrel where productid=? and taxid=?";
				$adb->pquery($sql, array($this->id,$taxid));
			}
		}
		for($i=0;$i<count($tax_details);$i++)
		{
			$tax_name = $tax_details[$i]['taxname'];
			$tax_checkname = $tax_details[$i]['taxname']."_check";
			if($_REQUEST[$tax_checkname] == 'on' || $_REQUEST[$tax_checkname] == 1)
			{
				$taxid = getTaxId($tax_name);
				$tax_per = $_REQUEST[$tax_name];
				if($tax_per == '')
				{
					$log->debug("Tax selected but value not given so default value will be saved.");
					$tax_per = getTaxPercentage($tax_name);
				}

				$log->debug("Going to save the Product - $tax_name tax relationship");

				$query = "insert into ncrm_producttaxrel values(?,?,?)";
				$adb->pquery($query, array($this->id,$taxid,$tax_per));
			}
		}

		$log->debug("Exiting from insertTaxInformation($tablename, $module) method ...");
	}

	/**	function to save the product price information in ncrm_productcurrencyrel table
	 *	@param string $tablename - ncrm_tablename to save the product currency relationship (productcurrencyrel)
	 *	@param string $module	 - current module name
	 *	$return void
	*/
	function insertPriceInformation($tablename, $module)
	{
		global $adb, $log, $current_user;
		$log->debug("Entering into insertPriceInformation($tablename, $module) method ...");
		//removed the update of currency_id based on the logged in user's preference : fix 6490

		$currency_details = getAllCurrencies('all');

		//Delete the existing currency relationship if any
		if($this->mode == 'edit' && $_REQUEST['action'] !== 'MassEditSave')
		{
			for($i=0;$i<count($currency_details);$i++)
			{
				$curid = $currency_details[$i]['curid'];
				$sql = "delete from ncrm_productcurrencyrel where productid=? and currencyid=?";
				$adb->pquery($sql, array($this->id,$curid));
			}
		}

		$product_base_conv_rate = getBaseConversionRateForProduct($this->id, $this->mode);
		$currencySet = 0;
		//Save the Product - Currency relationship if corresponding currency check box is enabled
		for($i=0;$i<count($currency_details);$i++)
		{
			$curid = $currency_details[$i]['curid'];
			$curname = $currency_details[$i]['currencylabel'];
			$cur_checkname = 'cur_' . $curid . '_check';
			$cur_valuename = 'curname' . $curid;

			$requestPrice = CurrencyField::convertToDBFormat($_REQUEST['unit_price'], null, true);
			$actualPrice = CurrencyField::convertToDBFormat($_REQUEST[$cur_valuename], null, true);
			if($_REQUEST[$cur_checkname] == 'on' || $_REQUEST[$cur_checkname] == 1)
			{
				$conversion_rate = $currency_details[$i]['conversionrate'];
				$actual_conversion_rate = $product_base_conv_rate * $conversion_rate;
				$converted_price = $actual_conversion_rate * $requestPrice;

				$log->debug("Going to save the Product - $curname currency relationship");

				$query = "insert into ncrm_productcurrencyrel values(?,?,?,?)";
				$adb->pquery($query, array($this->id,$curid,$converted_price,$actualPrice));

				// Update the Product information with Base Currency choosen by the User.
				if ($_REQUEST['base_currency'] == $cur_valuename) {
					$currencySet = 1;
					$adb->pquery("update ncrm_products set currency_id=?, unit_price=? where productid=?", array($curid, $actualPrice, $this->id));
				}
			}
			if(!$currencySet){
				$curid = fetchCurrency($current_user->id);
				$adb->pquery("update ncrm_products set currency_id=? where productid=?", array($curid, $this->id));
			}
		}

		$log->debug("Exiting from insertPriceInformation($tablename, $module) method ...");
	}

	function updateUnitPrice() {
		$prod_res = $this->db->pquery("select unit_price, currency_id from ncrm_products where productid=?", array($this->id));
		$prod_unit_price = $this->db->query_result($prod_res, 0, 'unit_price');
		$prod_base_currency = $this->db->query_result($prod_res, 0, 'currency_id');

		$query = "update ncrm_productcurrencyrel set actual_price=? where productid=? and currencyid=?";
		$params = array($prod_unit_price, $this->id, $prod_base_currency);
		$this->db->pquery($query, $params);
	}

	function insertIntoAttachment($id,$module)
	{
		global  $log,$adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
			      if($_REQUEST[$fileindex.'_hidden'] != '')
				      $files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
			      else
				      $files['original_name'] = stripslashes($files['name']);
			      $files['original_name'] = str_replace('"','',$files['original_name']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		//Updating image information in main table of products
		$existingImageSql = 'SELECT name FROM ncrm_seattachmentsrel INNER JOIN ncrm_attachments ON
								ncrm_seattachmentsrel.attachmentsid = ncrm_attachments.attachmentsid LEFT JOIN ncrm_products ON
								ncrm_products.productid = ncrm_seattachmentsrel.crmid WHERE ncrm_seattachmentsrel.crmid = ?';
		$existingImages = $adb->pquery($existingImageSql,array($id));
		$numOfRows = $adb->num_rows($existingImages);
		$productImageMap = array();

		for ($i = 0; $i < $numOfRows; $i++) {
			$imageName = $adb->query_result($existingImages, $i, "name");
			array_push($productImageMap, decode_html($imageName));
		}
		$commaSeperatedFileNames = implode(",", $productImageMap);

		$adb->pquery('UPDATE ncrm_products SET imagename = ? WHERE productid = ?',array($commaSeperatedFileNames,$id));

		//Remove the deleted ncrm_attachments from db - Products
		if($module == 'Products' && $_REQUEST['del_file_list'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select ncrm_attachments.attachmentsid from ncrm_attachments inner join ncrm_seattachmentsrel on ncrm_attachments.attachmentsid=ncrm_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');

				$del_res1 = $adb->pquery("delete from ncrm_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from ncrm_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}



	/**	function used to get the list of leads which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_leads($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_leads(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT ncrm_leaddetails.leadid, ncrm_crmentity.crmid, ncrm_leaddetails.firstname, ncrm_leaddetails.lastname, ncrm_leaddetails.company, ncrm_leadaddress.phone, ncrm_leadsubdetails.website, ncrm_leaddetails.email, case when (ncrm_users.user_name not like \"\") then ncrm_users.user_name else ncrm_groups.groupname end as user_name, ncrm_crmentity.smownerid, ncrm_products.productname, ncrm_products.qty_per_unit, ncrm_products.unit_price, ncrm_products.expiry_date
			FROM ncrm_leaddetails
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_leaddetails.leadid
			INNER JOIN ncrm_leadaddress ON ncrm_leadaddress.leadaddressid = ncrm_leaddetails.leadid
			INNER JOIN ncrm_leadsubdetails ON ncrm_leadsubdetails.leadsubscriptionid = ncrm_leaddetails.leadid
			INNER JOIN ncrm_seproductsrel ON ncrm_seproductsrel.crmid=ncrm_leaddetails.leadid
			INNER JOIN ncrm_products ON ncrm_seproductsrel.productid = ncrm_products.productid
			INNER JOIN ncrm_leadscf ON ncrm_leaddetails.leadid = ncrm_leadscf.leadid
			LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0 AND ncrm_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_leads method ...");
		return $return_value;
	}

	/**	function used to get the list of accounts which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_accounts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_accounts(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT ncrm_account.accountid, ncrm_crmentity.crmid, ncrm_account.accountname, ncrm_accountbillads.bill_city, ncrm_account.website, ncrm_account.phone, case when (ncrm_users.user_name not like \"\") then ncrm_users.user_name else ncrm_groups.groupname end as user_name, ncrm_crmentity.smownerid, ncrm_products.productname, ncrm_products.qty_per_unit, ncrm_products.unit_price, ncrm_products.expiry_date
			FROM ncrm_account
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_account.accountid
			INNER JOIN ncrm_accountbillads ON ncrm_accountbillads.accountaddressid = ncrm_account.accountid
            LEFT JOIN ncrm_accountshipads ON ncrm_accountshipads.accountaddressid = ncrm_account.accountid
			INNER JOIN ncrm_seproductsrel ON ncrm_seproductsrel.crmid=ncrm_account.accountid
			INNER JOIN ncrm_products ON ncrm_seproductsrel.productid = ncrm_products.productid
			INNER JOIN ncrm_accountscf ON ncrm_account.accountid = ncrm_accountscf.accountid
			LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0 AND ncrm_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_accounts method ...");
		return $return_value;
	}

	/**	function used to get the list of contacts which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT ncrm_contactdetails.firstname, ncrm_contactdetails.lastname, ncrm_contactdetails.title, ncrm_contactdetails.accountid, ncrm_contactdetails.email, ncrm_contactdetails.phone, ncrm_crmentity.crmid, case when (ncrm_users.user_name not like \"\") then ncrm_users.user_name else ncrm_groups.groupname end as user_name, ncrm_crmentity.smownerid, ncrm_products.productname, ncrm_products.qty_per_unit, ncrm_products.unit_price, ncrm_products.expiry_date,ncrm_account.accountname
			FROM ncrm_contactdetails
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_contactdetails.contactid
			INNER JOIN ncrm_seproductsrel ON ncrm_seproductsrel.crmid=ncrm_contactdetails.contactid
			INNER JOIN ncrm_contactaddress ON ncrm_contactdetails.contactid = ncrm_contactaddress.contactaddressid
			INNER JOIN ncrm_contactsubdetails ON ncrm_contactdetails.contactid = ncrm_contactsubdetails.contactsubscriptionid
			INNER JOIN ncrm_customerdetails ON ncrm_contactdetails.contactid = ncrm_customerdetails.customerid
			INNER JOIN ncrm_contactscf ON ncrm_contactdetails.contactid = ncrm_contactscf.contactid
			INNER JOIN ncrm_products ON ncrm_seproductsrel.productid = ncrm_products.productid
			LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_account ON ncrm_account.accountid = ncrm_contactdetails.accountid
			WHERE ncrm_crmentity.deleted = 0 AND ncrm_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}


	/**	function used to get the list of potentials which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_opportunities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_potential.potentialid, ncrm_crmentity.crmid,
			ncrm_potential.potentialname, ncrm_account.accountname, ncrm_potential.related_to, ncrm_potential.contact_id,
			ncrm_potential.sales_stage, ncrm_potential.amount, ncrm_potential.closingdate,
			case when (ncrm_users.user_name not like '') then $userNameSql else
			ncrm_groups.groupname end as user_name, ncrm_crmentity.smownerid,
			ncrm_products.productname, ncrm_products.qty_per_unit, ncrm_products.unit_price,
			ncrm_products.expiry_date FROM ncrm_potential
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_potential.potentialid
			INNER JOIN ncrm_seproductsrel ON ncrm_seproductsrel.crmid = ncrm_potential.potentialid
			INNER JOIN ncrm_products ON ncrm_seproductsrel.productid = ncrm_products.productid
			INNER JOIN ncrm_potentialscf ON ncrm_potential.potentialid = ncrm_potentialscf.potentialid
			LEFT JOIN ncrm_account ON ncrm_potential.related_to = ncrm_account.accountid
			LEFT JOIN ncrm_contactdetails ON ncrm_potential.contact_id = ncrm_contactdetails.contactid
			LEFT JOIN ncrm_users ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0 AND ncrm_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}

	/**	function used to get the list of tickets which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_tickets(".$id.") method ...");
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'product_id','readwrite') == '0') {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT  case when (ncrm_users.user_name not like \"\") then $userNameSql else ncrm_groups.groupname end as user_name, ncrm_users.id,
			ncrm_products.productid, ncrm_products.productname,
			ncrm_troubletickets.ticketid,
			ncrm_troubletickets.parent_id, ncrm_troubletickets.title,
			ncrm_troubletickets.status, ncrm_troubletickets.priority,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_crmentity.modifiedtime, ncrm_troubletickets.ticket_no
			FROM ncrm_troubletickets
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_troubletickets.ticketid
			LEFT JOIN ncrm_products
				ON ncrm_products.productid = ncrm_troubletickets.product_id
			LEFT JOIN ncrm_ticketcf ON ncrm_troubletickets.ticketid = ncrm_ticketcf.ticketid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_products.productid = ".$id;

		$log->debug("Exiting get_tickets method ...");

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
		return $return_value;
	}

	/**	function used to get the list of activities which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_activities($id)
	{
		global $log, $singlepane_view;
		$log->debug("Entering get_activities(".$id.") method ...");
		global $app_strings;

		require_once('modules/Calendar/Activity.php');

        	//if($this->column_fields['contact_id']!=0 && $this->column_fields['contact_id']!='')
        	$focus = new Activity();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_contactdetails.lastname,
			ncrm_contactdetails.firstname,
			ncrm_contactdetails.contactid,
			ncrm_activity.*,
			ncrm_seactivityrel.crmid as parent_id,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid,
			ncrm_crmentity.modifiedtime,
			$userNameSql,
			ncrm_recurringevents.recurringtype
			FROM ncrm_activity
			INNER JOIN ncrm_seactivityrel
				ON ncrm_seactivityrel.activityid = ncrm_activity.activityid
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid=ncrm_activity.activityid
			LEFT JOIN ncrm_cntactivityrel
				ON ncrm_cntactivityrel.activityid = ncrm_activity.activityid
			LEFT JOIN ncrm_contactdetails
				ON ncrm_contactdetails.contactid = ncrm_cntactivityrel.contactid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			LEFT OUTER JOIN ncrm_recurringevents
				ON ncrm_recurringevents.activityid = ncrm_activity.activityid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_seactivityrel.crmid=".$id."
			AND (activitytype != 'Emails')";
		$log->debug("Exiting get_activities method ...");
		return GetRelatedList('Products','Calendar',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of quotes which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_crmentity.*,
			ncrm_quotes.*,
			ncrm_potential.potentialname,
			ncrm_account.accountname,
			ncrm_inventoryproductrel.productid,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_quotes
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_quotes.quoteid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_quotes.quoteid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_quotes.accountid
			LEFT OUTER JOIN ncrm_potential
				ON ncrm_potential.potentialid = ncrm_quotes.potentialid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_quotescf
                ON ncrm_quotescf.quoteid = ncrm_quotes.quoteid
			LEFT JOIN ncrm_quotesbillads
				ON ncrm_quotesbillads.quotebilladdressid = ncrm_quotes.quoteid
			LEFT JOIN ncrm_quotesshipads
				ON ncrm_quotesshipads.quoteshipaddressid = ncrm_quotes.quoteid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_inventoryproductrel.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	/**	function used to get the list of purchase orders which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_crmentity.*,
			ncrm_purchaseorder.*,
			ncrm_products.productname,
			ncrm_inventoryproductrel.productid,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_purchaseorder
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_purchaseorder.purchaseorderid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_purchaseorder.purchaseorderid
			INNER JOIN ncrm_products
				ON ncrm_products.productid = ncrm_inventoryproductrel.productid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_purchaseordercf
                ON ncrm_purchaseordercf.purchaseorderid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_pobillads
				ON ncrm_pobillads.pobilladdressid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_poshipads
				ON ncrm_poshipads.poshipaddressid = ncrm_purchaseorder.purchaseorderid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
	}

	/**	function used to get the list of sales orders which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesorder(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_crmentity.*,
			ncrm_salesorder.*,
			ncrm_products.productname AS productname,
			ncrm_account.accountname,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_salesorder
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_salesorder.salesorderid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_salesorder.salesorderid
			INNER JOIN ncrm_products
				ON ncrm_products.productid = ncrm_inventoryproductrel.productid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_salesorder.accountid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_salesordercf
                ON ncrm_salesordercf.salesorderid = ncrm_salesorder.salesorderid
            LEFT JOIN ncrm_invoice_recurring_info
                ON ncrm_invoice_recurring_info.start_period = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_sobillads
				ON ncrm_sobillads.sobilladdressid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_soshipads
				ON ncrm_soshipads.soshipaddressid = ncrm_salesorder.salesorderid
			LEFT JOIN ncrm_users
				ON ncrm_users.id = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_products.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
		return $return_value;
	}

	/**	function used to get the list of invoices which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_invoices($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_invoices(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT ncrm_crmentity.*,
			ncrm_invoice.*,
			ncrm_inventoryproductrel.quantity,
			ncrm_account.accountname,
			case when (ncrm_users.user_name not like '') then $userNameSql
				else ncrm_groups.groupname end as user_name
			FROM ncrm_invoice
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_invoice.invoiceid
			LEFT OUTER JOIN ncrm_account
				ON ncrm_account.accountid = ncrm_invoice.accountid
			INNER JOIN ncrm_inventoryproductrel
				ON ncrm_inventoryproductrel.id = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
            LEFT JOIN ncrm_invoicecf
                ON ncrm_invoicecf.invoiceid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoicebillads
				ON ncrm_invoicebillads.invoicebilladdressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_invoiceshipads
				ON ncrm_invoiceshipads.invoiceshipaddressid = ncrm_invoice.invoiceid
			LEFT JOIN ncrm_users
				ON  ncrm_users.id=ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_inventoryproductrel.productid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_invoices method ...");
		return $return_value;
	}

	/**	function used to get the list of pricebooks which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_product_pricebooks($id, $cur_tab_id, $rel_tab_id, $actions=false)
	{
		global $log,$singlepane_view,$currentModule;
		$log->debug("Entering get_product_pricebooks(".$id.") method ...");

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$focus = new $related_module();
		$singular_modname = vtlib_toSingular($related_module);

		$button = '';
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes' && isPermitted($currentModule,'EditView',$id) == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_TO'). " ". getTranslatedString($related_module) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"AddProductToPriceBooks\";this.form.module.value=\"$currentModule\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_TO'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}

		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&return_id='.$id;


		$query = "SELECT ncrm_crmentity.crmid,
			ncrm_pricebook.*,
			ncrm_pricebookproductrel.productid as prodid
			FROM ncrm_pricebook
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_pricebook.pricebookid
			INNER JOIN ncrm_pricebookproductrel
				ON ncrm_pricebookproductrel.pricebookid = ncrm_pricebook.pricebookid
			INNER JOIN ncrm_pricebookcf
				ON ncrm_pricebookcf.pricebookid = ncrm_pricebook.pricebookid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_pricebookproductrel.productid = ".$id;
		$log->debug("Exiting get_product_pricebooks method ...");

		$return_value = GetRelatedList($currentModule, $related_module, $focus, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		return $return_value;
	}

	/**	function used to get the number of vendors which are related to the product
	 *	@param int $id - product id
	 *	@return int number of rows - return the number of products which do not have relationship with vendor
	 */
	function product_novendor()
	{
		global $log;
		$log->debug("Entering product_novendor() method ...");
		$query = "SELECT ncrm_products.productname, ncrm_crmentity.deleted
			FROM ncrm_products
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_products.productid
			WHERE ncrm_crmentity.deleted = 0
			AND ncrm_products.vendor_id is NULL";
		$result=$this->db->pquery($query, array());
		$log->debug("Exiting product_novendor method ...");
		return $this->db->num_rows($result);
	}

	/**
	* Function to get Product's related Products
	* @param  integer   $id      - productid
	* returns related Products record in array format
	*/
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
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

		if($actions && $this->ismember_check() === 0) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input type='hidden' name='createmode' id='createmode' value='link' />".
					"<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT ncrm_products.productid, ncrm_products.productname,
			ncrm_products.productcode, ncrm_products.commissionrate,
			ncrm_products.qty_per_unit, ncrm_products.unit_price,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid
			FROM ncrm_products
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_products.productid
			INNER JOIN ncrm_productcf
				ON ncrm_products.productid = ncrm_productcf.productid
			LEFT JOIN ncrm_seproductsrel ON ncrm_seproductsrel.crmid = ncrm_products.productid AND ncrm_seproductsrel.setype='Products'
			LEFT JOIN ncrm_users
				ON ncrm_users.id=ncrm_crmentity.smownerid
			LEFT JOIN ncrm_groups
				ON ncrm_groups.groupid = ncrm_crmentity.smownerid
			WHERE ncrm_crmentity.deleted = 0 AND ncrm_seproductsrel.productid = $id ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**
	* Function to get Product's related Products
	* @param  integer   $id      - productid
	* returns related Products record in array format
	*/
	function get_parent_products($id)
	{
		global $log, $singlepane_view;
                $log->debug("Entering get_products(".$id.") method ...");

		global $app_strings;

		$focus = new Products();

		$button = '';

		if(isPermitted("Products",1,"") == 'yes')
		{
			$button .= '<input title="'.$app_strings['LBL_NEW_PRODUCT'].'" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Products\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
		}
		if($singlepane_view == 'true')
			$returnset = '&return_module=Products&return_action=DetailView&is_parent=1&return_id='.$id;
		else
			$returnset = '&return_module=Products&return_action=CallRelatedList&is_parent=1&return_id='.$id;

		$query = "SELECT ncrm_products.productid, ncrm_products.productname,
			ncrm_products.productcode, ncrm_products.commissionrate,
			ncrm_products.qty_per_unit, ncrm_products.unit_price,
			ncrm_crmentity.crmid, ncrm_crmentity.smownerid
			FROM ncrm_products
			INNER JOIN ncrm_crmentity ON ncrm_crmentity.crmid = ncrm_products.productid
			INNER JOIN ncrm_seproductsrel ON ncrm_seproductsrel.productid = ncrm_products.productid AND ncrm_seproductsrel.setype='Products'
			INNER JOIN ncrm_productcf ON ncrm_products.productid = ncrm_productcf.productid

			WHERE ncrm_crmentity.deleted = 0 AND ncrm_seproductsrel.crmid = $id ";

		$log->debug("Exiting get_products method ...");
		return GetRelatedList('Products','Products',$focus,$query,$button,$returnset);
	}

	/**	function used to get the export query for product
	 *	@param reference $where - reference of the where variable which will be added with the query
	 *	@return string $query - return the query which will give the list of products to export
	 */
	function create_export_query($where)
	{
		global $log, $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Products", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM ".$this->table_name ."
			INNER JOIN ncrm_crmentity
				ON ncrm_crmentity.crmid = ncrm_products.productid
			LEFT JOIN ncrm_productcf
				ON ncrm_products.productid = ncrm_productcf.productid
			LEFT JOIN ncrm_vendor
				ON ncrm_vendor.vendorid = ncrm_products.vendor_id";

		$query .= " LEFT JOIN ncrm_groups ON ncrm_groups.groupid = ncrm_crmentity.smownerid";
		$query .= " LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid = ncrm_users.id AND ncrm_users.status='Active'";
		$query .= $this->getNonAdminAccessControlQuery('Products',$current_user);
		$where_auto = " ncrm_crmentity.deleted=0";

		if($where != '') $query .= " WHERE ($where) AND $where_auto";
		else $query .= " WHERE $where_auto";

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/** Function to check if the product is parent of any other product
	*/
	function isparent_check(){
		global $adb;
		$isparent_query = $adb->pquery(getListQuery("Products")." AND (ncrm_products.productid IN (SELECT productid from ncrm_seproductsrel WHERE ncrm_seproductsrel.productid = ? AND ncrm_seproductsrel.setype='Products'))",array($this->id));
		$isparent = $adb->num_rows($isparent_query);
		return $isparent;
	}

	/** Function to check if the product is member of other product
	*/
	function ismember_check(){
		global $adb;
		$ismember_query = $adb->pquery(getListQuery("Products")." AND (ncrm_products.productid IN (SELECT crmid from ncrm_seproductsrel WHERE ncrm_seproductsrel.crmid = ? AND ncrm_seproductsrel.setype='Products'))",array($this->id));
		$ismember = $adb->num_rows($ismember_query);
		return $ismember;
	}

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

		$rel_table_arr = Array("HelpDesk"=>"ncrm_troubletickets","Products"=>"ncrm_seproductsrel","Attachments"=>"ncrm_seattachmentsrel",
				"Quotes"=>"ncrm_inventoryproductrel","PurchaseOrder"=>"ncrm_inventoryproductrel","SalesOrder"=>"ncrm_inventoryproductrel",
				"Invoice"=>"ncrm_inventoryproductrel","PriceBooks"=>"ncrm_pricebookproductrel","Leads"=>"ncrm_seproductsrel",
				"Accounts"=>"ncrm_seproductsrel","Potentials"=>"ncrm_seproductsrel","Contacts"=>"ncrm_seproductsrel",
				"Documents"=>"ncrm_senotesrel",'Assets'=>'ncrm_assets',);

		$tbl_field_arr = Array("ncrm_troubletickets"=>"ticketid","ncrm_seproductsrel"=>"crmid","ncrm_seattachmentsrel"=>"attachmentsid",
				"ncrm_inventoryproductrel"=>"id","ncrm_pricebookproductrel"=>"pricebookid","ncrm_seproductsrel"=>"crmid",
				"ncrm_senotesrel"=>"notesid",'ncrm_assets'=>'assetsid');

		$entity_tbl_field_arr = Array("ncrm_troubletickets"=>"product_id","ncrm_seproductsrel"=>"crmid","ncrm_seattachmentsrel"=>"crmid",
				"ncrm_inventoryproductrel"=>"productid","ncrm_pricebookproductrel"=>"productid","ncrm_seproductsrel"=>"productid",
				"ncrm_senotesrel"=>"crmid",'ncrm_assets'=>'product');

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
						" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
						array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
							array($entityId,$transferId,$id_field_value));
					}
				}
			}
		}
		$log->debug("Exiting transferRelatedRecords...");
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner) {
		global $current_user;
		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("ncrm_crmentityProducts",array("ncrm_groupsProducts","ncrm_usersProducts","ncrm_lastModifiedByProducts"));
		$matrix->setDependency("ncrm_products",array("innerProduct","ncrm_crmentityProducts","ncrm_productcf","ncrm_vendorRelProducts"));
		//query planner Support  added
		if (!$queryplanner->requireTable('ncrm_products', $matrix)) {
			return '';
		}
		$query = $this->getRelationQuery($module,$secmodule,"ncrm_products","productid", $queryplanner);
		if ($queryplanner->requireTable("innerProduct")){
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
		if ($queryplanner->requireTable("ncrm_crmentityProducts")){
		    $query .= " left join ncrm_crmentity as ncrm_crmentityProducts on ncrm_crmentityProducts.crmid=ncrm_products.productid and ncrm_crmentityProducts.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_productcf")){
		    $query .= " left join ncrm_productcf on ncrm_products.productid = ncrm_productcf.productid";
		}
    		if ($queryplanner->requireTable("ncrm_groupsProducts")){
		    $query .= " left join ncrm_groups as ncrm_groupsProducts on ncrm_groupsProducts.groupid = ncrm_crmentityProducts.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_usersProducts")){
		    $query .= " left join ncrm_users as ncrm_usersProducts on ncrm_usersProducts.id = ncrm_crmentityProducts.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_vendorRelProducts")){
		    $query .= " left join ncrm_vendor as ncrm_vendorRelProducts on ncrm_vendorRelProducts.vendorid = ncrm_products.vendor_id";
		}
		if ($queryplanner->requireTable("ncrm_lastModifiedByProducts")){
		    $query .= " left join ncrm_users as ncrm_lastModifiedByProducts on ncrm_lastModifiedByProducts.id = ncrm_crmentityProducts.modifiedby ";
		}
        if ($queryplanner->requireTable("ncrm_createdbyProducts")){
			$query .= " left join ncrm_users as ncrm_createdbyProducts on ncrm_createdbyProducts.id = ncrm_crmentityProducts.smcreatorid ";
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
			"HelpDesk" => array("ncrm_troubletickets"=>array("product_id","ticketid"),"ncrm_products"=>"productid"),
			"Quotes" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_products"=>"productid"),
			"PurchaseOrder" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_products"=>"productid"),
			"SalesOrder" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_products"=>"productid"),
			"Invoice" => array("ncrm_inventoryproductrel"=>array("productid","id"),"ncrm_products"=>"productid"),
			"Leads" => array("ncrm_seproductsrel"=>array("productid","crmid"),"ncrm_products"=>"productid"),
			"Accounts" => array("ncrm_seproductsrel"=>array("productid","crmid"),"ncrm_products"=>"productid"),
			"Contacts" => array("ncrm_seproductsrel"=>array("productid","crmid"),"ncrm_products"=>"productid"),
			"Potentials" => array("ncrm_seproductsrel"=>array("productid","crmid"),"ncrm_products"=>"productid"),
			"Products" => array("ncrm_products"=>array("productid","product_id"),"ncrm_products"=>"productid"),
			"PriceBooks" => array("ncrm_pricebookproductrel"=>array("productid","pricebookid"),"ncrm_products"=>"productid"),
			"Documents" => array("ncrm_senotesrel"=>array("crmid","notesid"),"ncrm_products"=>"productid"),
		);
		return $rel_tables[$secmodule];
	}

	function deleteProduct2ProductRelation($record,$return_id,$is_parent){
		global $adb;
		if($is_parent==0){
			$sql = "delete from ncrm_seproductsrel WHERE crmid = ? AND productid = ?";
			$adb->pquery($sql, array($record,$return_id));
		} else {
			$sql = "delete from ncrm_seproductsrel WHERE crmid = ? AND productid = ?";
			$adb->pquery($sql, array($return_id,$record));
		}
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		//Backup Campaigns-Product Relation
		$cmp_q = 'SELECT campaignid FROM ncrm_campaign WHERE product_id = ?';
		$cmp_res = $this->db->pquery($cmp_q, array($id));
		if ($this->db->num_rows($cmp_res) > 0) {
			$cmp_ids_list = array();
			for($k=0;$k < $this->db->num_rows($cmp_res);$k++)
			{
				$cmp_ids_list[] = $this->db->query_result($cmp_res,$k,"campaignid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'ncrm_campaign', 'product_id', 'campaignid', implode(",", $cmp_ids_list));
			$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//we have to update the product_id as null for the campaigns which are related to this product
		$this->db->pquery('UPDATE ncrm_campaign SET product_id=0 WHERE product_id = ?', array($id));

		$this->db->pquery('DELETE from ncrm_seproductsrel WHERE productid=? or crmid=?',array($id,$id));

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Calendar') {
			$sql = 'DELETE FROM ncrm_seactivityrel WHERE crmid = ? AND activityid = ?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Leads' || $return_module == 'Contacts' || $return_module == 'Potentials') {
			$sql = 'DELETE FROM ncrm_seproductsrel WHERE productid = ? AND crmid = ?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Vendors') {
			$sql = 'UPDATE ncrm_products SET vendor_id = ? WHERE productid = ?';
			$this->db->pquery($sql, array(null, $id));
		} elseif($return_module == 'Accounts') {
			$sql = 'DELETE FROM ncrm_seproductsrel WHERE productid = ? AND (crmid = ? OR crmid IN (SELECT contactid FROM ncrm_contactdetails WHERE accountid=?))';
			$param = array($id, $return_id,$return_id);
			$this->db->pquery($sql, $param);
		} else {
			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Leads' || $with_module == 'Accounts' ||
					$with_module == 'Contacts' || $with_module == 'Potentials' || $with_module == 'Products'){
				$query = $adb->pquery("SELECT * from ncrm_seproductsrel WHERE crmid=? and productid=?",array($crmid, $with_crmid));
				if($adb->num_rows($query)==0){
					$adb->pquery("insert into ncrm_seproductsrel values (?,?,?)", array($with_crmid, $crmid, $with_module));
				}
			}
			else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

}
?>
