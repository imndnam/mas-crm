<?php

/* +**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * ********************************************************************************** */

/**
 * Provides basic API to work with NCRM Fields
 * @package vtlib
 */
class Ncrm_FieldBasic {

    /** ID of this field instance */
    var $id;
    var $name;
    var $label = false;
    var $table = false;
    var $column = false;
    var $columntype = false;
    var $helpinfo = '';
    var $summaryfield = 0;
    var $masseditable = 1; // Default: Enable massedit for field
    var $uitype = 1;
    var $typeofdata = 'V~O';
    var $displaytype = 1;
    var $generatedtype = 1;
    var $readonly = 1;
    var $presence = 2;
    var $defaultvalue = '';
    var $maximumlength = 100;
    var $sequence = false;
    var $quickcreate = 1;
    var $quicksequence = false;
    var $info_type = 'BAS';
    var $block;

    /**
     * Constructor
     */
    function __construct() {
        
    }

    /**
     * Initialize this instance
     * @param Array 
     * @param Ncrm_Module Instance of module to which this field belongs
     * @param Ncrm_Block Instance of block to which this field belongs
     * @access private
     */
    function initialize($valuemap, $moduleInstance = false, $blockInstance = false) {
        $this->id = $valuemap['fieldid'];
        $this->name = $valuemap['fieldname'];
        $this->label = $valuemap['fieldlabel'];
        $this->column = $valuemap['columnname'];
        $this->table = $valuemap['tablename'];
        $this->uitype = $valuemap['uitype'];
        $this->typeofdata = $valuemap['typeofdata'];
        $this->helpinfo = $valuemap['helpinfo'];
        $this->masseditable = $valuemap['masseditable'];
        $this->displaytype = $valuemap['displaytype'];
        $this->generatedtype = $valuemap['generatedtype'];
        $this->readonly = $valuemap['readonly'];
        $this->presence = $valuemap['presence'];
        $this->defaultvalue = $valuemap['defaultvalue'];
        $this->quickcreate = $valuemap['quickcreate'];
        $this->sequence = $valuemap['sequence'];
        $this->summaryfield = $valuemap['summaryfield'];
        $this->block = $blockInstance ? $blockInstance : Ncrm_Block::getInstance($valuemap['block'], $moduleInstance);
    }

    /** Cache (Record) the schema changes to improve performance */
    static $__cacheSchemaChanges = Array();

    /**
     * Initialize ncrm schema changes.
     * @access private
     */
    function __handleNcrmCoreSchemaChanges() {
// Add helpinfo column to the ncrm_field table
        if (empty(self::$__cacheSchemaChanges['ncrm_field.helpinfo'])) {
            Ncrm_Utils::AddColumn('ncrm_field', 'helpinfo', ' TEXT');
            self::$__cacheSchemaChanges['ncrm_field.helpinfo'] = true;
        }
        if (empty(self::$__cacheSchemaChanges['ncrm_field.summaryfield'])) {
            Ncrm_Utils::AddColumn('ncrm_field', 'summaryfield', ' INT(10) NOT NULL DEFAULT 0');
            self::$__cacheSchemaChanges['ncrm_field.summaryfield'] = 0;
        }
    }

    /**
     * Get unique id for this instance
     * @access private
     */
    function __getUniqueId() {
        global $adb;
        return $adb->getUniqueID('ncrm_field');
    }

    /**
     * Get next sequence id to use within a block for this instance
     * @access private
     */
    function __getNextSequence() {
        global $adb;
        $result = $adb->pquery("SELECT MAX(sequence) AS max_seq FROM ncrm_field WHERE tabid=? AND block=?", Array($this->getModuleId(), $this->getBlockId()));
        $maxseq = 0;
        if ($result && $adb->num_rows($result)) {
            $maxseq = $adb->query_result($result, 0, 'max_seq');
            $maxseq += 1;
        }
        return $maxseq;
    }

    /**
     * Get next quick create sequence id for this instance
     * @access private
     */
    function __getNextQuickCreateSequence() {
        global $adb;
        $result = $adb->pquery("SELECT MAX(quickcreatesequence) AS max_quickcreateseq FROM ncrm_field WHERE tabid=?", Array($this->getModuleId()));
        $max_quickcreateseq = 0;
        if ($result && $adb->num_rows($result)) {
            $max_quickcreateseq = $adb->query_result($result, 0, 'max_quickcreateseq');
            $max_quickcreateseq += 1;
        }
        return $max_quickcreateseq;
    }

    /**
     * Create this field instance
     * @param Ncrm_Block Instance of the block to use
     * @access private
     */
    function __create($blockInstance) {
        $this->__handleNcrmCoreSchemaChanges();

        global $adb;

        $this->block = $blockInstance;

        $moduleInstance = $this->getModuleInstance();

        $this->id = $this->__getUniqueId();

        if (!$this->sequence) {
            $this->sequence = $this->__getNextSequence();
        }

        if ($this->quickcreate != 1) { // If enabled for display
            if (!$this->quicksequence) {
                $this->quicksequence = $this->__getNextQuickCreateSequence();
            }
        } else {
            $this->quicksequence = null;
        }

// Initialize other variables which are not done
        if (!$this->table)
            $this->table = $moduleInstance->basetable;
        if (!$this->column) {
            $this->column = strtolower($this->name);
            if (!$this->columntype)
                $this->columntype = 'VARCHAR(100)';
        }

        if (!$this->label)
            $this->label = $this->name;

        $adb->pquery("INSERT INTO ncrm_field (tabid, fieldid, columnname, tablename, generatedtype,
uitype, fieldname, fieldlabel, readonly, presence, defaultvalue, maximumlength, sequence,
block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type, helpinfo, summaryfield) 
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", Array($this->getModuleId(), $this->id, $this->column, $this->table, intval($this->generatedtype),
            $this->uitype, $this->name, $this->label, $this->readonly, $this->presence, $this->defaultvalue,
            $this->maximumlength, $this->sequence, $this->getBlockId(), $this->displaytype, $this->typeofdata,
            intval($this->quickcreate), intval($this->quicksequence), $this->info_type, $this->helpinfo, intval($this->summaryfield)));

// Set the field status for mass-edit (if set)
        $adb->pquery('UPDATE ncrm_field SET masseditable=? WHERE fieldid=?', Array($this->masseditable, $this->id));

        Ncrm_Profile::initForField($this);

        if (!empty($this->columntype)) {
            Ncrm_Utils::AddColumn($this->table, $this->column, $this->columntype);
        }

        self::log("Creating Field $this->name ... DONE");
        self::log("Module language mapping for $this->label ... CHECK");
    }

    /**
     * Update this field instance
     * @access private
     * @internal TODO
     */
    function __update() {
        self::log("Updating Field $this->name ... DONE");
    }

    /**
     * Delete this field instance
     * @access private
     */
    function __delete() {
        global $adb;

        Ncrm_Profile::deleteForField($this);

        $adb->pquery("DELETE FROM ncrm_field WHERE fieldid=?", Array($this->id));
        self::log("Deleteing Field $this->name ... DONE");
    }

    /**
     * Get block id to which this field instance is associated
     */
    function getBlockId() {
        return $this->block->id;
    }

    /**
     * Get module id to which this field instance is associated
     */
    function getModuleId() {
        return $this->block->module->id;
    }

    /**
     * Get module name to which this field instance is associated
     */
    function getModuleName() {
        return $this->block->module->name;
    }

    /**
     * Get module instance to which this field instance is associated
     */
    function getModuleInstance() {
        return $this->block->module;
    }

    /**
     * Save this field instance
     * @param Ncrm_Block Instance of block to which this field should be added.
     */
    function save($blockInstance = false) {
        if ($this->id)
            $this->__update();
        else
            $this->__create($blockInstance);
        return $this->id;
    }

    /**
     * Delete this field instance
     */
    function delete() {
        $this->__delete();
    }

    /**
     * Set Help Information for this instance.
     * @param String Help text (content)
     */
    function setHelpInfo($helptext) {
// Make sure to initialize the core tables first
        $this->__handleNcrmCoreSchemaChanges();

        global $adb;
        $adb->pquery('UPDATE ncrm_field SET helpinfo=? WHERE fieldid=?', Array($helptext, $this->id));
        self::log("Updated help information of $this->name ... DONE");
    }

    /**
     * Set Masseditable information for this instance.
     * @param Integer Masseditable value
     */
    function setMassEditable($value) {
        global $adb;
        $adb->pquery('UPDATE ncrm_field SET masseditable=? WHERE fieldid=?', Array($value, $this->id));
        self::log("Updated masseditable information of $this->name ... DONE");
    }
    
    /** 
     * Set Summaryfield information for this instance. 
     * @param Integer Summaryfield value 
     */ 
    function setSummaryField($value) { 
        global $adb; 
        $adb->pquery('UPDATE ncrm_field SET summaryfield=? WHERE fieldid=?', Array($value, $this->id)); 
        self::log("Updated summaryfield information of $this->name ... DONE"); 
    } 

    /**
     * Helper function to log messages
     * @param String Message to log
     * @param Boolean true appends linebreak, false to avoid it
     * @access private
     */
    static function log($message, $delim = true) {
        Ncrm_Utils::Log($message, $delim);
    }
}
?>