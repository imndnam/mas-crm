<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
	
	function vtws_getchallenge($username){
		
		global $adb;
		
		$user = new Users();
		$userid = $user->retrieve_user_id($username);
		$authToken = uniqid();
		
		$servertime = time();
		$expireTime = time()+(60*5);
		
		$sql = "delete from ncrm_ws_userauthtoken where userid=?";
		$adb->pquery($sql,array($userid));
		
		$sql = "insert into ncrm_ws_userauthtoken(userid,token,expireTime) values (?,?,?)";
		$adb->pquery($sql,array($userid,$authToken,$expireTime));
		
		return array("token"=>$authToken,"serverTime"=>$servertime,"expireTime"=>$expireTime);
	}

?>