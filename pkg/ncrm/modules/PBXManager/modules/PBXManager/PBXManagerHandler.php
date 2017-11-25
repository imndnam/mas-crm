<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class PBXManagerHandler extends VTEventHandler {

    function handleEvent($eventName, $entityData) {
        $moduleName = $entityData->getModuleName();

        $acceptedModule = array('Contacts','Accounts','Leads');
        if(!in_array($moduleName, $acceptedModule))
            return;
        
        if ($eventName == 'ncrm.entity.aftersave') {
            PBXManagerHandler::handlePhoneLookUpSaveEvent($entityData, $moduleName);
        }
        
        if($eventName == 'ncrm.entity.afterdelete'){
            PBXManagerHandler::handlePhoneLookupDeleteEvent($entityData);
        }
        
        if($eventName == 'ncrm.entity.afterrestore'){
            $this->handlePhoneLookUpRestoreEvent($entityData, $moduleName);
        }
    }

    static function handlePhoneLookUpSaveEvent($entityData, $moduleName) {
        $recordid = $entityData->getId();
        $data = $entityData->getData();
        
        $values['crmid'] = $recordid;
        $values['setype'] = $moduleName;
        $recordModel = new PBXManager_Record_Model;

        $moduleInstance = Ncrm_Module_Model::getInstance($moduleName);
        $fieldsModel = $moduleInstance->getFieldsByType('phone');
        
        foreach ($fieldsModel as $field => $fieldName) {
                $fieldName = $fieldName->get('name');      
                $values[$fieldName] = $data[$fieldName];
                
                if($values[$fieldName])
                    $recordModel->receivePhoneLookUpRecord($fieldName, $values, true);
        }
    }
    
    static function handlePhoneLookupDeleteEvent($entityData){
        $recordid = $entityData->getId();
        $recordModel = new PBXManager_Record_Model;
        $recordModel->deletePhoneLookUpRecord($recordid);
    }
    
    protected function handlePhoneLookUpRestoreEvent($entityData, $moduleName) {
        $recordid = $entityData->getId();

        //To get the record model of the restored record
        $recordmodel = Ncrm_Record_Model::getInstanceById($recordid, $moduleName);

        $values['crmid'] = $recordid;
        $values['setype'] = $moduleName;
        $recordModel = new PBXManager_Record_Model;

        $moduleInstance = Ncrm_Module_Model::getInstance($moduleName);
        $fieldsModel = $moduleInstance->getFieldsByType('phone');
        
        foreach ($fieldsModel as $field => $fieldName) {
            $fieldName = $fieldName->get('name');  
            $values[$fieldName] = $recordmodel->get($fieldName);
            
            if($values[$fieldName])
                 $recordModel->receivePhoneLookUpRecord($fieldName, $values, true);
        }
    }

}

class PBXManagerBatchHandler extends VTEventHandler {
    
    function handleEvent($eventName, $entityDatas) {
        foreach ($entityDatas as $entityData) {
            $moduleName = $entityData->getModuleName();

            $acceptedModule = array('Contacts','Accounts','Leads');
            if(!in_array($moduleName, $acceptedModule))
                return;

            if ($eventName == 'ncrm.batchevent.save') {
                PBXManagerHandler::handlePhoneLookUpSaveEvent($entityData, $moduleName);
            }
            
            if ($eventName == 'ncrm.batchevent.delete') {
                PBXManagerHandler::handlePhoneLookupDeleteEvent($entityData);
            }
        }
    }
}

?>