<?php
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *
 ********************************************************************************/
include_once 'modules/Invoice/InvoicePDFController.php';
global $currentModule;

$controller = new Ncrm_InvoicePDFController($currentModule);
$controller->loadRecord(vtlib_purify($_REQUEST['record']));
$invoice_no = getModuleSequenceNumber($currentModule,vtlib_purify($_REQUEST['record']));
$translatedmodname= vtranslate($currentModule,$currentModule);
if(isset($_REQUEST['savemode']) && $_REQUEST['savemode'] == 'file') {
	$id = vtlib_purify($_REQUEST['record']);
	$filepath='test/product/'.$id.'_'.$translatedmodname.'_'.$invoice_no.'.pdf';
	$controller->Output($filepath,'F'); //added file name to make it work in IE, also forces the download giving the user the option to save
} else {
	$controller->Output($translatedmodname.'_'.$invoice_no.'.pdf', 'D');//added file name to make it work in IE, also forces the download giving the user the option to save
	exit();
}

?>
