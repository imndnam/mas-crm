<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

function Contacts_sendCustomerPortalLoginDetails($entityData){
	$adb = PearDatabase::getInstance();
	$moduleName = $entityData->getModuleName();
	$wsId = $entityData->getId();
	$parts = explode('x', $wsId);
	$entityId = $parts[1];
	$entityDelta = new VTEntityDelta();
	$portalChanged = $entityDelta->hasChanged($moduleName, $entityId, 'portal');
	$email = $entityData->get('email');

	if ($entityData->get('portal') == 'on' || $entityData->get('portal') == '1') {
		$sql = "SELECT id, user_name, user_password, isactive FROM ncrm_portalinfo WHERE id=?";
		$result = $adb->pquery($sql, array($entityId));
		$insert = false;
		if($adb->num_rows($result) == 0){
			$insert = true;
		}else{
			$dbusername = $adb->query_result($result,0,'user_name');
			$isactive = $adb->query_result($result,0,'isactive');
			if($email == $dbusername && $isactive == 1 && !$entityData->isNew()){
				$update = false;
			} else if($entityData->get('portal') == 'on' ||  $entityData->get('portal') == '1'){
				$sql = "UPDATE ncrm_portalinfo SET user_name=?, isactive=? WHERE id=?";
				$adb->pquery($sql, array($email, 1, $entityId));
				$update = true;
			} else {
				$sql = "UPDATE ncrm_portalinfo SET user_name=?, isactive=? WHERE id=?";
				$adb->pquery($sql, array($email, 0, $entityId));
				$update = false;
			}
		}
		$password = makeRandomPassword();
		$enc_password = Ncrm_Functions::generateEncryptedPassword($password);
		if ($insert == true) {
			$sql = "INSERT INTO ncrm_portalinfo(id,user_name,user_password,cryptmode,type,isactive) VALUES(?,?,?,?,?,?)";
			$params = array($entityId, $email, $enc_password, 'CRYPT', 'C', 1);
			$adb->pquery($sql, $params);
		}
		if ($update == true && $portalChanged == true) {
			$sql = "UPDATE ncrm_portalinfo SET user_password=?, cryptmode=? WHERE id=?";
			$params = array($enc_password, 'CRYPT', $entityId);
			$adb->pquery($sql, $params);
		}
		if (($insert == true || ($update = true && $portalChanged == true)) && $entityData->get('emailoptout') == 0) {
			global $current_user,$HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;
			require_once("modules/Emails/mail.php");
			$emailData = Contacts::getPortalEmailContents($entityData,$password,'LoginDetails');
			$subject = $emailData['subject'];
            if(empty($subject)) {
                $subject = 'Customer Portal Login Details';
            }
			$contents = $emailData['body'];
            $contents= decode_html(getMergedDescription($contents, $entityId, 'Contacts'));
            if(empty($contents)) {
				require_once 'config.inc.php';
				global $PORTAL_URL;
                $contents = 'LoginDetails';
                $contents .= "<br><br> User ID : ".$entityData->get('email');
                $contents .= "<br> Password: ".$password;
				$portalURL = vtranslate('Please ',$moduleName).'<a href="'.$PORTAL_URL.'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;">'.  vtranslate('click here', $moduleName).'</a>';
				$contents .= "<br>".$portalURL;
            }
            $subject=  decode_html(getMergedDescription($subject, $entityId,'Contacts'));
			send_mail('Contacts', $entityData->get('email'), $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_ID, $subject, $contents,'','','','','',true);
		}
	} else {
		$sql = "UPDATE ncrm_portalinfo SET user_name=?,isactive=0 WHERE id=?";
		$adb->pquery($sql, array($email, $entityId));
	}
}


function Contacts_UpdateUTMFields($entityData){
    $adb = PearDatabase::getInstance();
    $moduleName = $entityData->getModuleName();
    $wsId = $entityData->getId();
    $parts = explode('x', $wsId);
    $entityId = $parts[1];
    $link = $entityData->get('cf_769');

    if(empty($link)) return false;

    // http://fid.topica.vn?utm_source=facebook&utm_team=FID40&utm_agent=FID40&utm_term=FID40&utm_medium=cpm&utm_content=Maits_CVS02_L2-1%_Mol1_BQTC_CBH2_V9

    parse_str($link, $params);

    $utmSource = isset($params['utm_source']) ? $params['utm_source'] : '';
    $utmTeam = isset($params['utm_team']) ? $params['utm_team'] : '';
    $utmAgent = isset($params['utm_agent']) ? $params['utm_agent'] : '';
    $utmTerm = isset($params['utm_term']) ? $params['utm_term'] : '';
    $utmMedium = isset($params['utm_medium']) ? $params['utm_medium'] : '';
    $utmContent = isset($params['utm_content']) ? $params['utm_content'] : '';

    $adb->pquery("UPDATE ncrm_contactscf SET cf_755 = ?, cf_757 = ?, cf_759 = ?, cf_761 = ?, cf_763 = ?, cf_765 = ? WHERE contactid = ?", array($utmSource, $utmTeam, $utmAgent, $utmTerm, $utmMedium, $utmContent, $entityId));
}

?>
