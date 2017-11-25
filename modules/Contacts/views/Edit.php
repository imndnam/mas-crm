<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Contacts_Edit_View extends Ncrm_Edit_View {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
        $recordModel = $this->record;
        if(!$recordModel){
           if (!empty($recordId)) {
               $recordModel = Ncrm_Record_Model::getInstanceById($recordId, $moduleName);
           } else {
               $recordModel = Ncrm_Record_Model::getCleanInstance($moduleName);
           }
            $this->record = $recordModel;
        }

		$viewer = $this->getViewer($request);
		$viewer->assign('IMAGE_DETAILS', $recordModel->getImageDetails());

		$salutationFieldModel = Ncrm_Field_Model::getInstance('salutationtype', $recordModel->getModule());
		// Fix for http://trac.ncrm.com/cgi-bin/trac.cgi/ticket/7851
		$salutationType = $request->get('salutationtype');
		if(!empty($salutationType)){ 
                    $salutationFieldModel->set('fieldvalue', $request->get('salutationtype')); 
                } 
                else{ 
                    $salutationFieldModel->set('fieldvalue', $recordModel->get('salutationtype')); 
                } 
		$viewer->assign('SALUTATION_FIELD_MODEL', $salutationFieldModel);

		parent::process($request);
	}

}
