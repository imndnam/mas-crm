<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class ModComments_CommentsModel {
	private $data;
	
	static $ownerNamesCache = array();
	
	function __construct($datarow) {
		$this->data = $datarow;
	}
	
	function author() {
		$authorid = $this->data['smcreatorid'];
		if(!isset(self::$ownerNamesCache[$authorid])) {
			self::$ownerNamesCache[$authorid] = getOwnerName($authorid);
		}
		return self::$ownerNamesCache[$authorid];
	}
	
	function timestamp(){
		$date = new DateTimeField($this->data['modifiedtime']);
		return $date->getDisplayDateTimeValue();
	}
	
	function content() {
		return decode_html($this->data['commentcontent']);
	}
}