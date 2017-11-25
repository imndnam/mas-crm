<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

function vtws_logout($sessionId,$user){
        global $adb;
        $sql = "select type from ncrm_ws_operation where name=?";
        $result = $adb->pquery($sql,array("logout"));
        $row = $adb->query_result_rowdata($result,0);
        $requestType = $row['type'];
        if($_SERVER['REQUEST_METHOD'] != $requestType){
            throw new WebServiceException(WebServiceErrorCode::$OPERATIONNOTSUPPORTED, "Permission to perform the operation is denied");
        }
	$sessionManager = new SessionManager();
	$sid = $sessionManager->startSession($sessionId);
	
	if(!isset($sessionId) || !$sessionManager->isValid()){
		return $sessionManager->getError();
	}

	$sessionManager->destroy();
//	$sessionManager->setExpire(1);
	return array("message"=>"successfull");

}
?>
