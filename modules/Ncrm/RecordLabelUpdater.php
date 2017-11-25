<?php
/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */
require_once 'include/events/VTEventHandler.inc';

class Ncrm_RecordLabelUpdater_Handler extends VTEventHandler {

	function handleEvent($eventName, $data) {
		global $adb;

		if ($eventName == 'ncrm.entity.aftersave') {
			$labelInfo = getEntityName($data->getModuleName(), $data->getId(), true);

			if ($labelInfo) {
				$label = decode_html($labelInfo[$data->getId()]);
				$adb->pquery('UPDATE ncrm_crmentity SET label=? WHERE crmid=?', array($label, $data->getId()));
			}
		}
	}
}