<?php
/*+********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/

// Note is used to store customer information.
class Documents extends CRMEntity {

	var $log;
	var $db;
	var $table_name = "ncrm_notes";
	var $table_index= 'notesid';
	var $default_note_name_dom = array('Meeting ncrm_notes', 'Reminder');

	var $tab_name = Array('ncrm_crmentity','ncrm_notes','ncrm_notescf');
	var $tab_name_index = Array('ncrm_crmentity'=>'crmid','ncrm_notes'=>'notesid','ncrm_senotesrel'=>'notesid','ncrm_notescf'=>'notesid');

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('ncrm_notescf', 'notesid');

	var $column_fields = Array();

    var $sortby_fields = Array('title','modifiedtime','filename','createdtime','lastname','filedownloadcount','smownerid');

	// This is used to retrieve related ncrm_fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of ncrm_fields that are in the lists.
	var $list_fields = Array(
				'Title'=>Array('notes'=>'title'),
				'File Name'=>Array('notes'=>'filename'),
				'Modified Time'=>Array('crmentity'=>'modifiedtime'),
				'Assigned To' => Array('crmentity'=>'smownerid'),
				'Folder Name' => Array('attachmentsfolder'=>'folderid')
				);
	var $list_fields_name = Array(
					'Title'=>'notes_title',
					'File Name'=>'filename',
					'Modified Time'=>'modifiedtime',
					'Assigned To'=>'assigned_user_id',
					'Folder Name' => 'folderid'
				     );

	var $search_fields = Array(
					'Title' => Array('notes'=>'notes_title'),
					'File Name' => Array('notes'=>'filename'),
					'Assigned To' => Array('crmentity'=>'smownerid'),
					'Folder Name' => Array('attachmentsfolder'=>'foldername')
		);

	var $search_fields_name = Array(
					'Title' => 'notes_title',
					'File Name' => 'filename',
					'Assigned To' => 'assigned_user_id',
					'Folder Name' => 'folderid'
	);
	var $list_link_field= 'notes_title';
	var $old_filename = '';
	//var $groupTable = Array('ncrm_notegrouprelation','notesid');

	var $mandatory_fields = Array('notes_title','createdtime' ,'modifiedtime','filename','filesize','filetype','filedownloadcount','assigned_user_id');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'title';
	var $default_sort_order = 'ASC';
	function Documents() {
		$this->log = LoggerManager::getLogger('notes');
		$this->log->debug("Entering Documents() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Documents');
		$this->log->debug("Exiting Documents method ...");
	}

	function save_module($module)
	{
		global $log,$adb,$upload_badext;
		$insertion_mode = $this->mode;
		if(isset($this->parentid) && $this->parentid != '')
			$relid =  $this->parentid;
		//inserting into ncrm_senotesrel
		if(isset($relid) && $relid != '')
		{
			$this->insertintonotesrel($relid,$this->id);
		}
		$filetype_fieldname = $this->getFileTypeFieldName();
		$filename_fieldname = $this->getFile_FieldName();
		if($this->column_fields[$filetype_fieldname] == 'I' ){
			if($_FILES[$filename_fieldname]['name'] != ''){
				$errCode=$_FILES[$filename_fieldname]['error'];
					if($errCode == 0){
						foreach($_FILES as $fileindex => $files)
						{
							if($files['name'] != '' && $files['size'] > 0){
								$filename = $_FILES[$filename_fieldname]['name'];
								$filename = from_html(preg_replace('/\s+/', '_', $filename));
								$filetype = $_FILES[$filename_fieldname]['type'];
								$filesize = $_FILES[$filename_fieldname]['size'];
								$filelocationtype = 'I';
								$binFile = sanitizeUploadFileName($filename, $upload_badext);
								$filename = ltrim(basename(" ".$binFile)); //allowed filename like UTF-8 characters
							}
						}

					}
			}elseif($this->mode == 'edit') {
				$fileres = $adb->pquery("select filetype, filesize,filename,filedownloadcount,filelocationtype from ncrm_notes where notesid=?", array($this->id));
				if ($adb->num_rows($fileres) > 0) {
					$filename = $adb->query_result($fileres, 0, 'filename');
					$filetype = $adb->query_result($fileres, 0, 'filetype');
					$filesize = $adb->query_result($fileres, 0, 'filesize');
					$filedownloadcount = $adb->query_result($fileres, 0, 'filedownloadcount');
					$filelocationtype = $adb->query_result($fileres, 0, 'filelocationtype');
				}
			}elseif($this->column_fields[$filename_fieldname]) {
				$filename = $this->column_fields[$filename_fieldname];
				$filesize = $this->column_fields['filesize'];
				$filetype = $this->column_fields['filetype'];
				$filelocationtype = $this->column_fields[$filetype_fieldname];
				$filedownloadcount = 0;
			} else {
				$filelocationtype = 'I';
				$filetype = '';
				$filesize = 0;
				$filedownloadcount = null;
			}
		} else if($this->column_fields[$filetype_fieldname] == 'E' ){
			$filelocationtype = 'E';
			$filename = $this->column_fields[$filename_fieldname];
			// If filename does not has the protocol prefix, default it to http://
			// Protocol prefix could be like (https://, smb://, file://, \\, smb:\\,...)
			if(!empty($filename) && !preg_match('/^\w{1,5}:\/\/|^\w{0,3}:?\\\\\\\\/', trim($filename), $match)) {
				$filename = "http://$filename";
			}
			$filetype = '';
			$filesize = 0;
			$filedownloadcount = null;
		}
		$query = "UPDATE ncrm_notes SET filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? , filedownloadcount = ? WHERE notesid = ?";
 		$re=$adb->pquery($query,array(decode_html($filename),$filesize,$filetype,$filelocationtype,$filedownloadcount,$this->id));
		//Inserting into attachments table
		if($filelocationtype == 'I') {
			$this->insertIntoAttachment($this->id,'Documents');
		}else{
			$query = "delete from ncrm_seattachmentsrel where crmid = ?";
			$qparams = array($this->id);
			$adb->pquery($query, $qparams);
		}
        //set the column_fields so that its available in the event handlers
        $this->column_fields['filename'] = $filename;
        $this->column_fields['filesize'] = $filesize;
        $this->column_fields['filetype'] = $filetype;
        $this->column_fields['filedownloadcount'] = $filedownloadcount;
	}


	/**
	 *      This function is used to add the ncrm_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the ncrm_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	/**    Function used to get the sort order for Documents listview
	*      @return string  $sorder - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['NOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	*/
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['NOTES_SORT_ORDER'] != '')?($_SESSION['NOTES_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**     Function used to get the order by value for Documents listview
	*       @return string  $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['NOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
	*/
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");

		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		if (isset($_REQUEST['order_by']))
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		else
			$order_by = (($_SESSION['NOTES_ORDER_BY'] != '')?($_SESSION['NOTES_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**
	 * Function used to get the sort order for Documents listview
	 * @return String $sorder - sort order for a given folder.
	 */
	function getSortOrderForFolder($folderId) {
		if(isset($_REQUEST['sorder']) && $_REQUEST['folderid'] == $folderId) {
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		} elseif(is_array($_SESSION['NOTES_FOLDER_SORT_ORDER']) &&
					!empty($_SESSION['NOTES_FOLDER_SORT_ORDER'][$folderId])) {
				$sorder = $_SESSION['NOTES_FOLDER_SORT_ORDER'][$folderId];
		} else {
			$sorder = $this->default_sort_order;
		}
		return $sorder;
	}

	/**
	 * Function used to get the order by value for Documents listview
	 * @return String order by column for a given folder.
	 */
	function getOrderByForFolder($folderId) {
		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		if (isset($_REQUEST['order_by'])  && $_REQUEST['folderid'] == $folderId) {
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		} elseif(is_array($_SESSION['NOTES_FOLDER_ORDER_BY']) &&
				!empty($_SESSION['NOTES_FOLDER_ORDER_BY'][$folderId])) {
			$order_by = $_SESSION['NOTES_FOLDER_ORDER_BY'][$folderId];
		} else {
			$order_by = ($use_default_order_by);
		}
		return $order_by;
	}

	/** Function to export the notes in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Documents Query.
	*/
	function create_export_query($where)
	{
		global $log,$current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Documents", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'ncrm_users.first_name', 'last_name' => 'ncrm_users.last_name'), 'Users');
		$query = "SELECT $fields_list, case when (ncrm_users.user_name not like '') then $userNameSql else ncrm_groups.groupname end as user_name" .
				" FROM ncrm_notes
				inner join ncrm_crmentity
					on ncrm_crmentity.crmid=ncrm_notes.notesid
				LEFT JOIN ncrm_attachmentsfolder on ncrm_notes.folderid=ncrm_attachmentsfolder.folderid
				LEFT JOIN ncrm_users ON ncrm_crmentity.smownerid=ncrm_users.id " .
				" LEFT JOIN ncrm_groups ON ncrm_crmentity.smownerid=ncrm_groups.groupid "
				;
		$query .= getNonAdminAccessControlQuery('Documents',$current_user);
		$where_auto=" ncrm_crmentity.deleted=0";
		if($where != "")
			$query .= "  WHERE ($where) AND ".$where_auto;
		else
			$query .= "  WHERE ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		        return $query;
	}

	function del_create_def_folder($query)
	{
		global $adb;
		$dbQuery = $query." and ncrm_attachmentsfolderfolderid.folderid = 0";
		$dbresult = $adb->pquery($dbQuery,array());
		$noofnotes = $adb->num_rows($dbresult);
		if($noofnotes > 0)
		{
            $folderQuery = "select folderid from ncrm_attachmentsfolder";
            $folderresult = $adb->pquery($folderQuery,array());
            $noofdeffolders = $adb->num_rows($folderresult);

            if($noofdeffolders == 0)
            {
			    $insertQuery = "insert into ncrm_attachmentsfolder values (0,'Default','Contains all attachments for which a folder is not set',1,0)";
			    $insertresult = $adb->pquery($insertQuery,array());
            }
		}

	}

	function insertintonotesrel($relid,$id)
	{
		global $adb;
		$dbQuery = "insert into ncrm_senotesrel values ( ?, ? )";
		$dbresult = $adb->pquery($dbQuery,array($relid,$id));
	}

	/*function save_related_module($module, $crmid, $with_module, $with_crmid){
	}*/


	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module,$queryplanner){
		$moduletable = $this->table_name;
		$moduleindex = $this->tab_name_index[$moduletable];
		$query = "from $moduletable
			inner join ncrm_crmentity on ncrm_crmentity.crmid=$moduletable.$moduleindex";
		if ($queryplanner->requireTable("ncrm_attachmentsfolder")){
		    $query .= " inner join ncrm_attachmentsfolder on ncrm_attachmentsfolder.folderid=$moduletable.folderid";
		}
		if ($queryplanner->requireTable("ncrm_groups".$module)){
		    $query .= " left join ncrm_groups as ncrm_groups".$module." on ncrm_groups".$module.".groupid = ncrm_crmentity.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_users".$module)){
		    $query .= " left join ncrm_users as ncrm_users".$module." on ncrm_users".$module.".id = ncrm_crmentity.smownerid";
		}
		$query .= " left join ncrm_groups on ncrm_groups.groupid = ncrm_crmentity.smownerid";
        $query .= " left join ncrm_notescf on ncrm_notes.notesid = ncrm_notescf.notesid";
		$query .= " left join ncrm_users on ncrm_users.id = ncrm_crmentity.smownerid";
		if ($queryplanner->requireTable("ncrm_lastModifiedBy".$module)){
		    $query .= " left join ncrm_users as ncrm_lastModifiedBy".$module." on ncrm_lastModifiedBy".$module.".id = ncrm_crmentity.modifiedby ";
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

		$matrix->setDependency("ncrm_crmentityDocuments",array("ncrm_groupsDocuments","ncrm_usersDocuments","ncrm_lastModifiedByDocuments"));
		$matrix->setDependency("ncrm_notes",array("ncrm_crmentityDocuments","ncrm_attachmentsfolder"));

		if (!$queryplanner->requireTable('ncrm_notes', $matrix)) {
			return '';
		}
		// TODO Support query planner
		$query = $this->getRelationQuery($module,$secmodule,"ncrm_notes","notesid", $queryplanner);
        $query .= " left join ncrm_notescf on ncrm_notes.notesid = ncrm_notescf.notesid";
		if ($queryplanner->requireTable("ncrm_crmentityDocuments",$matrix)){
		    $query .=" left join ncrm_crmentity as ncrm_crmentityDocuments on ncrm_crmentityDocuments.crmid=ncrm_notes.notesid and ncrm_crmentityDocuments.deleted=0";
		}
		if ($queryplanner->requireTable("ncrm_attachmentsfolder")){
		    $query .=" left join ncrm_attachmentsfolder on ncrm_attachmentsfolder.folderid=ncrm_notes.folderid";
		}
		if ($queryplanner->requireTable("ncrm_groupsDocuments")){
		    $query .=" left join ncrm_groups as ncrm_groupsDocuments on ncrm_groupsDocuments.groupid = ncrm_crmentityDocuments.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_usersDocuments")){
		    $query .=" left join ncrm_users as ncrm_usersDocuments on ncrm_usersDocuments.id = ncrm_crmentityDocuments.smownerid";
		}
		if ($queryplanner->requireTable("ncrm_lastModifiedByDocuments")){
		    $query .=" left join ncrm_users as ncrm_lastModifiedByDocuments on ncrm_lastModifiedByDocuments.id = ncrm_crmentityDocuments.modifiedby ";
		}
        if ($queryplanner->requireTable("ncrm_createdbyDocuments")){
			$query .= " left join ncrm_users as ncrm_createdbyDocuments on ncrm_createdbyDocuments.id = ncrm_crmentityDocuments.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array();
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		/*//Backup Documents Related Records
		$se_q = 'SELECT crmid FROM ncrm_senotesrel WHERE notesid = ?';
		$se_res = $this->db->pquery($se_q, array($id));
		if ($this->db->num_rows($se_res) > 0) {
			for($k=0;$k < $this->db->num_rows($se_res);$k++)
			{
				$se_id = $this->db->query_result($se_res,$k,"crmid");
				$params = array($id, RB_RECORD_DELETED, 'ncrm_senotesrel', 'notesid', 'crmid', $se_id);
				$this->db->pquery('INSERT INTO ncrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		$sql = 'DELETE FROM ncrm_senotesrel WHERE notesid = ?';
		$this->db->pquery($sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$sql = 'DELETE FROM ncrm_senotesrel WHERE notesid = ? AND (crmid = ? OR crmid IN (SELECT contactid FROM ncrm_contactdetails WHERE accountid=?))';
			$this->db->pquery($sql, array($id, $return_id, $return_id));
		} else {
			$sql = 'DELETE FROM ncrm_senotesrel WHERE notesid = ? AND crmid = ?';
			$this->db->pquery($sql, array($id, $return_id));

			$sql = 'DELETE FROM ncrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}


// Function to get fieldname for uitype 27 assuming that documents have only one file type field

	function getFileTypeFieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from ncrm_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filetype_uitype = 27;
		$res = $adb->pquery($query,array($tabid,$filetype_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;

	}

//	Function to get fieldname for uitype 28 assuming that doc has only one file upload type

	function getFile_FieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from ncrm_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filename_uitype = 28;
		$res = $adb->pquery($query,array($tabid,$filename_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;
	}

	/**
	 * Check the existence of folder by folderid
	 */
	function isFolderPresent($folderid) {
		global $adb;
		$result = $adb->pquery("SELECT folderid FROM ncrm_attachmentsfolder WHERE folderid = ?", array($folderid));
		if(!empty($result) && $adb->num_rows($result) > 0) return true;
		return false;
	}

	/**
	 * Customizing the restore procedure.
	 */
	function restore($modulename, $id) {
		parent::restore($modulename, $id);

		global $adb;
		$fresult = $adb->pquery("SELECT folderid FROM ncrm_notes WHERE notesid = ?", array($id));
		if(!empty($fresult) && $adb->num_rows($fresult)) {
			$folderid = $adb->query_result($fresult, 0, 'folderid');
			if(!$this->isFolderPresent($folderid)) {
				// Re-link to default folder
				$adb->pquery("UPDATE ncrm_notes set folderid = 1 WHERE notesid = ?", array($id));
			}
		}
	}

	function getQueryByModuleField($module, $fieldname, $srcrecord, $query) {
		if($module == "MailManager") {
			$tempQuery = split('WHERE', $query);
			if(!empty($tempQuery[1])) {
				$where = " ncrm_notes.filelocationtype = 'I' AND ncrm_notes.filename != '' AND ncrm_notes.filestatus != 0 AND ";
				$query = $tempQuery[0].' WHERE '.$where.$tempQuery[1];
			} else{
				$query = $tempQuery[0].' WHERE '.$tempQuery;
			}
			return $query;
		}
	}

	/**
	 * Function to check the module active and user action permissions before showing as link in other modules
	 * like in more actions of detail view.
	 */
	static function isLinkPermitted($linkData) {
		$moduleName = "Documents";
		if(vtlib_isModuleActive($moduleName) && isPermitted($moduleName, 'EditView') == 'yes') {
			return true;
		}
		return false;
	}
}
?>