<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

	function vtws_extendSession(){
		global $adb,$API_VERSION,$application_unique_key;
		if(isset($_SESSION["authenticated_user_id"]) && $_SESSION["app_unique_key"] == $application_unique_key){
			$userId = $_SESSION["authenticated_user_id"];
			$sessionManager = new SessionManager();
			$sessionManager->set("authenticatedUserId", $userId);
			$crmObject = NcrmWebserviceObject::fromName($adb,"Users");
			$userId = vtws_getId($crmObject->getEntityId(),$userId);
			$ncrmVersion = vtws_getNcrmVersion();
			$resp = array("sessionName"=>$sessionManager->getSessionId(),"userId"=>$userId,"version"=>$API_VERSION,"ncrmVersion"=>$ncrmVersion);
			return $resp;
		}else{
			throw new WebServiceException(WebServiceErrorCode::$AUTHFAILURE,"Authencation Failed");
		}
	}
?>