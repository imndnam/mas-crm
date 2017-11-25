<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

$Module_Mobile_Configuration = array(

	'Default.Skin'     => 'default.css', // Available in resources/skins
	'Navigation.Limit' => 25,

	// Control number of records sent out through API (SyncModuleRecords, Query...) which supports paging.	
	'API_RECORD_FETCH_LIMIT' => 99, // NOTE: vtws_query internally limits fetch to 100 and give room to perform 1 extra fetch to determine paging
	
);

?>
