<?php

/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Currency_ListView_Model extends Settings_Ncrm_ListView_Model {
    
    public function getBasicListQuery() {
        $query = parent::getBasicListQuery();
        $query .= ' WHERE deleted=0 ';
        return $query;
    }
}