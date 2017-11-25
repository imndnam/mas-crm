<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_LayoutEditor_Field_Action extends Settings_Ncrm_Index_Action {

    function __construct() {
        $this->exposeMethod('add');
        $this->exposeMethod('save');
        $this->exposeMethod('delete');
        $this->exposeMethod('move');
        $this->exposeMethod('unHide');
    }

    public function add(Ncrm_Request $request) {
        $type = $request->get('fieldType');
        $moduleName = $request->get('sourceModule');
        $blockId = $request->get('blockid');
        $moduleModel = Settings_LayoutEditor_Module_Model::getInstanceByName($moduleName);
        $response = new Ncrm_Response();
        try{

            $fieldModel = $moduleModel->addField($type,$blockId,$request->getAll());
            $fieldInfo = $fieldModel->getFieldInfo();
            $responseData = array_merge(array('id'=>$fieldModel->getId(), 'blockid'=>$blockId, 'customField'=>$fieldModel->isCustomField()),$fieldInfo);
            $response->setResult($responseData);
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }

    public function save(Ncrm_Request $request) {
        $fieldId = $request->get('fieldid');
        $fieldInstance = Ncrm_Field_Model::getInstance($fieldId);
        $fieldInstance->updateTypeofDataFromMandatory($request->get('mandatory'))
					  ->set('presence', $request->get('presence'))
                      ->set('quickcreate', $request->get('quickcreate'))
					  ->set('summaryfield', $request->get('summaryfield'))
                      ->set('masseditable', $request->get('masseditable'));
		$defaultValue = $request->get('fieldDefaultValue');
		if($fieldInstance->getFieldDataType() == 'date') {
			$dateInstance = new Ncrm_Date_UIType();
			$defaultValue = $dateInstance->getDBInsertedValue($defaultValue);
		}

		if(is_array($defaultValue)) {
			$defaultValue = implode(' |##| ',$defaultValue);
		}
        $fieldInstance->set('defaultvalue', $defaultValue);
        $response = new Ncrm_Response();
        try{
            $fieldInstance->save();
            $response->setResult(array('success'=>true, 'presence'=>$request->get('presence'), 'mandatory'=>$fieldInstance->isMandatory(),
									'label'=>vtranslate($fieldInstance->get('label'), $request->get('sourceModule'))));
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }

    public function delete(Ncrm_Request $request) {
        $fieldId = $request->get('fieldid');
        $fieldInstance = Settings_LayoutEditor_Field_Model::getInstance($fieldId);
        $response = new Ncrm_Response();

        if(!$fieldInstance->isCustomField()) {
            $response->setError('122', 'Cannot delete Non custom field');
            $response->emit();
            return;
        }

        try{
            $fieldInstance->delete();
            $response->setResult(array('success'=>true));
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }

    public function move(Ncrm_Request $request) {
        $updatedFieldsList = $request->get('updatedFields');

		//This will update the fields sequence for the updated blocks
        Settings_LayoutEditor_Block_Model::updateFieldSequenceNumber($updatedFieldsList);

        $response = new Ncrm_Response();
		$response->setResult(array('success'=>true));
        $response->emit();
    }

    public function unHide(Ncrm_Request $request) {
        $response = new Ncrm_Response();
        try{
			$fieldIds = $request->get('fieldIdList');
            Settings_LayoutEditor_Field_Model::makeFieldActive($fieldIds, $request->get('blockId'));
			$responseData = array();
			foreach($fieldIds as $fieldId) {
				$fieldModel = Settings_LayoutEditor_Field_Model::getInstance($fieldId);
				$fieldInfo = $fieldModel->getFieldInfo();
				$responseData[] = array_merge(array('id'=>$fieldModel->getId(), 'blockid'=>$fieldModel->get('block')->id, 'customField'=>$fieldModel->isCustomField()),$fieldInfo);
			}
            $response->setResult($responseData);
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();

    }
    
    public function validateRequest(Ncrm_Request $request) {
        $request->validateWriteAccess(); 
    } 
}