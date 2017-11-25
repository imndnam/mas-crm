<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
class ConfigEditorHandler extends VTEventHandler {

	function handleEvent($eventName, $data) {

		if($eventName == 'ncrm.entity.beforesave') {
			// Entity is about to be saved, take required action
		}

		if($eventName == 'ncrm.entity.aftersave') {
			// Entity has been saved, take next action
		}
	}
}

?>