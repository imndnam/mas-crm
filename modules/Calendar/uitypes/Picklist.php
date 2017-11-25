<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Calendar_Picklist_UIType extends Ncrm_Picklist_UIType {
    
    
    public function getListSearchTemplateName() {
        
        $fieldName = $this->get('field')->get('name');
        
        if($fieldName == 'taskstatus') {
            return 'uitypes/StatusPickListFieldSearchView.tpl';
        }
        else if ($fieldName == 'activitytype') {
            return 'uitypes/ActivityPicklistFieldSearchView.tpl';
        }
            return parent::getListSearchTemplateName();
    }
}