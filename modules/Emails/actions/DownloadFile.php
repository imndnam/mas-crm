<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Emails_DownloadFile_Action extends Ncrm_Action_Controller {

	public function checkPermission(Ncrm_Request $request) {
		$moduleName = $request->getModule();

		if(!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'))) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
		}
	}

	public function process(Ncrm_Request $request) {
        $db = PearDatabase::getInstance();

        $attachmentId = $request->get('attachment_id');
        $query = "SELECT * FROM ncrm_attachments WHERE attachmentsid = ?" ;
        $result = $db->pquery($query, array($attachmentId));

        if($db->num_rows($result) == 1)
        {
            $row = $db->fetchByAssoc($result, 0);
            $fileType = $row["type"];
            $name = $row["name"];
            $filepath = $row["path"];
            $name = decode_html($name);
            $saved_filename = $attachmentId."_".$name;
            $disk_file_size = filesize($filepath.$saved_filename);
            $filesize = $disk_file_size + ($disk_file_size % 1024);
            $fileContent = fread(fopen($filepath.$saved_filename, "r"), $filesize);

            header("Content-type: $fileType");
            header("Pragma: public");
            header("Cache-Control: private");
            header("Content-Disposition: attachment; filename=$name");
            header("Content-Description: PHP Generated Data");
            echo $fileContent;
        }
    }
}

?>
