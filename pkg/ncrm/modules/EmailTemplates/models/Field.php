<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class EmailTemplates_Field_Model extends Ncrm_Field_Model {
    
    /**
	 * Function to check if the field is named field of the module
	 * @return <Boolean> - True/False
	 */
	public function isNameField() {
        return false;
    }
	
}