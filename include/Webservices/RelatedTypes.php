<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

function vtws_relatedtypes($elementType, $user) {
    global $adb, $log;

    $allowedTypes = vtws_listtypes(null, $user);

    $webserviceObject = NcrmWebserviceObject::fromName($adb, $elementType);
    $handlerPath  = $webserviceObject->getHandlerPath();
    $handlerClass = $webserviceObject->getHandlerClass();

    require_once $handlerPath;
    $handler = new $handlerClass($webserviceObject, $user, $adb, $log);
    $meta = $handler->getMeta();
    $tabid = $meta->getTabId();

    $sql = "SELECT ncrm_relatedlists.label, ncrm_tab.name, ncrm_tab.isentitytype FROM ncrm_relatedlists 
            INNER JOIN ncrm_tab ON ncrm_tab.tabid=ncrm_relatedlists.related_tabid 
            WHERE ncrm_relatedlists.tabid=? AND ncrm_tab.presence = 0";

    $params = array($tabid);
    $rs = $adb->pquery($sql, $params);

    $return = array('types' => array(), 'information' => array());

    while ($row = $adb->fetch_array($rs)) {
        if (in_array($row['name'], $allowedTypes['types'])) {
            $return['types'][] = $row['name'];
            // There can be same module related under different label - so label is our key.
            $return['information'][$row['label']] = array(
                'name' => $row['name'],
                'label'=> $row['label'],
                'isEntity' => $row['isentitytype']
            );
        }
    }

	return $return;
}

