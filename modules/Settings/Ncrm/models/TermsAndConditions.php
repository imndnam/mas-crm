<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Ncrm_TermsAndConditions_Model extends Ncrm_Base_Model{
    
    const tableName = 'ncrm_inventory_tandc';
    
    public function getText(){
        return $this->get('tandc');
    }
    
    public function setText($text){
        return $this->set('tandc',$text);
    }
    
    public function getType(){
        return "Inventory";
    }
    
    public function save() {
        $db = PearDatabase::getInstance();
        $query = 'SELECT 1 FROM '.self::tableName;
        $result = $db->pquery($query,array());
        if($db->num_rows($result) > 0) {
            $query = 'UPDATE '.self::tableName.' SET tandc=?';
            $params = array($this->getText());
        }else{
            $query = 'INSERT INTO '.self::tableName.' (id,type,tandc) VALUES(?,?,?)';
            $params = array($db->getUniqueID(self::tableName, $this->getType(), $this->getText()));
        }
        $result = $db->pquery($query, $params);
    }
    
    public static function getInstance() {
        $db = PearDatabase::getInstance();
        $query = 'SELECT tandc FROM '.self::tableName;
        $result = $db->pquery($query,array());
        $instance = new self();
        if($db->num_rows($result) > 0) {
            $text = $db->query_result($result,0,'tandc');
            $instance->setText($text);
        }
        return $instance;
    }
}