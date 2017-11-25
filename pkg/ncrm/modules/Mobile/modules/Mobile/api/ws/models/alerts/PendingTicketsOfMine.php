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

/** Pending Ticket Alert */
class Mobile_WS_AlertModel_PendingTicketsOfMine extends Mobile_WS_AlertModel {
	function __construct() {
		parent::__construct();
		$this->name = 'Pending Ticket Alert';
		$this->moduleName = 'HelpDesk';
		$this->refreshRate= 1 * (24* 60 * 60); // 1 day
		$this->description='Alert sent when ticket assigned is not yet closed';
	}
	
	function query() {
		$sql = "SELECT crmid FROM ncrm_troubletickets INNER JOIN 
				ncrm_crmentity ON ncrm_crmentity.crmid=ncrm_troubletickets.ticketid 
				WHERE ncrm_crmentity.deleted=0 AND ncrm_crmentity.smownerid=? AND 
				ncrm_troubletickets.status <> 'Closed'";
		return $sql;
	}
	
	function queryParameters() {
		return array($this->getUser()->id);
	}
}