<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

// TODO This is a stop-gap measure to have the
// user continue working with Calendar when dropping from Event View.
class Events_Calendar_View extends Ncrm_Index_View {
	
	public function preProcess(Ncrm_Request $request, $display = true) {}
	public function postProcess(Ncrm_Request $request) {}
	
	public function process(Ncrm_Request $request) {
		header("Location: index.php?module=Calendar&view=Calendar");
	}
}
