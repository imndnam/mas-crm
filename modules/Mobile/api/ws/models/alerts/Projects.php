<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/../Alert.php';
class Mobile_WS_AlertModel_Projects extends Mobile_WS_AlertModel {
	function __construct() {
		parent::__construct();
		$this->name = 'My Projects';
		$this->moduleName = 'Project';
		$this->refreshRate= 1 * (24* 60 * 60); // 1 day
		$this->description='Projects Related To Me';
	}

	function query() {
		$sql = "SELECT crmid FROM ncrm_crmentity INNER JOIN ncrm_project ON
                    ncrm_project.projectid=ncrm_crmentity.crmid WHERE ncrm_crmentity.deleted=0 AND ncrm_crmentity.smownerid=? AND
                    ncrm_project.projectstatus <> 'completed'";
		return $sql;
	}
        function queryParameters() {
		return array($this->getUser()->id);
	}

}

