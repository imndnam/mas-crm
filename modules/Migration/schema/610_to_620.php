<?php
/*+********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * ******************************************************************************* */
if(defined('NCRM_UPGRADE')) {
     updateVtlibModule('Google', 'packages/ncrm/optional/Google.zip');
}
if(defined('INSTALLATION_MODE')) {
		// Set of task to be taken care while specifically in installation mode.
}

//Handle migration for http://trac.ncrm.com/cgi-bin/trac.cgi/ticket/7552--senotesrel
$seDeleteQuery="DELETE from ncrm_senotesrel WHERE crmid NOT IN(select crmid from ncrm_crmentity)";
Migration_Index_View::ExecuteQuery($seDeleteQuery,array());
$seNotesSql="ALTER TABLE ncrm_senotesrel ADD CONSTRAINT fk1_crmid FOREIGN KEY(crmid) REFERENCES ncrm_crmentity(crmid) ON DELETE CASCADE";
Migration_Index_View::ExecuteQuery($seNotesSql,array());

//Update uitype of created_user_id field of ncrm_field from 53 to 52
$updateQuery = "UPDATE ncrm_field SET uitype = 52 WHERE fieldname = 'created_user_id'";
Migration_Index_View::ExecuteQuery($updateQuery,array());

/*141*/
//registering handlers for Google sync 
require_once 'includes/main/WebUI.php';
require_once 'modules/WSAPP/Utils.php'; 
require_once 'modules/Google/connectors/Config.php';
wsapp_RegisterHandler('Google_ncrmHandler', 'Google_Ncrm_Handler', 'modules/Google/handlers/Ncrm.php');
wsapp_RegisterHandler('Google_ncrmSyncHandler', 'Google_NcrmSync_Handler', 'modules/Google/handlers/NcrmSync.php');

//updating Google Sync Handler names 
$db = PearDatabase::getInstance();
$names = array('Ncrm_GoogleContacts', 'Ncrm_GoogleCalendar');
$result = $db->pquery("SELECT stateencodedvalues FROM ncrm_wsapp_sync_state WHERE name IN (".  generateQuestionMarks($names).")", array($names)); 
$resultRows = $db->num_rows($result); 
$appKey = array(); 
for($i=0; $i<$resultRows; $i++) { 
        $stateValuesJson = $db->query_result($result, $i, 'stateencodedvalues'); 
        $stateValues = Zend_Json::decode(decode_html($stateValuesJson)); 
        $appKey[] = $stateValues['synctrackerid']; 
}

if(!empty($appKey)) { 
    $sql = 'UPDATE ncrm_wsapp SET name = ? WHERE appkey IN ('.  generateQuestionMarks($appKey).')'; 
    $res = Migration_Index_View::ExecuteQuery($sql, array('Google_ncrmSyncHandler', $appKey)); 
}
        
//Ends 141

//Google Calendar sync changes
/**
 * Please refer this trac (http://trac.ncrm.com/cgi-bin/trac.cgi/ticket/8354#comment:3)
 * for configuration of ncrm to Google OAuth2
 */
global $adb;

if(!Ncrm_Utils::CheckTable('ncrm_google_oauth2')) {
    Ncrm_Utils::CreateTable('ncrm_google_oauth2',
            '(service varchar(20),access_token varchar(500),refresh_token varchar(500),userid int(19))',true);
    echo '<br> ncrm_google_oauth2 table created <br>';
}

//(start)Migrating GoogleCalendar ClientIds in wsapp_recordmapping to support v3
            
$syncTrackerIds = array();

if(Ncrm_Utils::CheckTable('ncrm_wsapp_sync_state')) {

    $sql = 'SELECT stateencodedvalues from ncrm_wsapp_sync_state WHERE name = ?';
    $result = $db->pquery($sql,array('Ncrm_GoogleCalendar'));
    $num_of_rows = $adb->num_rows($result);

    for($i=0;$i<$num_of_rows;$i++) {
        $stateEncodedValues = $adb->query_result($result,$i,'stateencodedvalues');
        $htmlDecodedStateEncodedValue = decode_html($stateEncodedValues);
        $stateDecodedValues = json_decode($htmlDecodedStateEncodedValue,true);
        if(is_array($stateDecodedValues) && isset($stateDecodedValues['synctrackerid'])) {
            $syncTrackerIds[] = $stateDecodedValues['synctrackerid'];
        }
    }

}

//$syncTrackerIds - list of all Calendar sync trackerIds

$appIds = array();

if(count($syncTrackerIds)) {

    $sql = 'SELECT appid FROM ncrm_wsapp WHERE appkey IN (' . generateQuestionMarks($syncTrackerIds) . ')';
    $result = Migration_Index_View::ExecuteQuery($sql,$syncTrackerIds);

    $num_of_rows = $adb->num_rows($result);

    for($i=0;$i<$num_of_rows;$i++) {
        $appId = $adb->query_result($result,$i,'appid');
        if($appId) $appIds[] = $appId;
    }

}

//$appIds - list of all Calendarsync appids

if(count($appIds)) {

    $sql = 'SELECT id,clientid FROM ncrm_wsapp_recordmapping WHERE appid IN (' . generateQuestionMarks($appIds) . ')';
    $result = Migration_Index_View::ExecuteQuery($sql,$appIds);

    $num_of_rows = $adb->num_rows($result);

    for($i=0;$i<$num_of_rows;$i++) {

        $id = $adb->query_result($result,$i,'id');
        $clientid = $adb->query_result($result,$i,'clientid');

        $parts = explode('/', $clientid);
        $newClientId = end($parts);

        Migration_Index_View::ExecuteQuery('UPDATE ncrm_wsapp_recordmapping SET clientid = ? WHERE id = ?',array($newClientId,$id));

    }

    echo '<br> ncrm_wsapp_recordmapping clientid migration completed for CalendarSync';

}
//(end)
            
//Google Calendar sync changes ends here

//Google migration : Create Sync setting table
$sql = 'CREATE TABLE ncrm_google_sync_settings (user int(11) DEFAULT NULL, 
    module varchar(50) DEFAULT NULL , clientgroup varchar(255) DEFAULT NULL, 
    direction varchar(50) DEFAULT NULL)';
$db->pquery($sql,array());
$sql = 'CREATE TABLE ncrm_google_sync_fieldmapping ( ncrm_field varchar(255) DEFAULT NULL,
        google_field varchar(255) DEFAULT NULL, google_field_type varchar(255) DEFAULT NULL,
        google_custom_label varchar(255) DEFAULT NULL, user int(11) DEFAULT NULL)';
$db->pquery($sql,array());
echo '<br>Google sync setting and mapping table added</br>';