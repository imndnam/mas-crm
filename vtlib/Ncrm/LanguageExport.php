<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Ncrm/Package.php');

/**
 * Provides API to package NCRM language files.
 * @package vtlib
 */
class Ncrm_LanguageExport extends Ncrm_Package {

	const TABLENAME = 'ncrm_language';

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Generate unique id for insertion
	 * @access private
	 */
	static function __getUniqueId() {
		global $adb;
		return $adb->getUniqueID(self::TABLENAME);
	}
	
	/**
	 * Initialize Export
	 * @access private
	 */
	function __initExport($languageCode, $moduleInstance = null) {
		// Security check to ensure file is withing the web folder.
		Ncrm_Utils::checkFileAccessForInclusion("languages/$languageCode/Ncrm.php");
		
		$this->_export_modulexml_file = fopen($this->__getManifestFilePath(), 'w');
		$this->__write("<?xml version='1.0'?>\n");
	}
	
	/**
	 * Export Module as a zip file.
	 * @param Ncrm_Module Instance of module
	 * @param Path Output directory path
	 * @param String Zipfilename to use
	 * @param Boolean True for sending the output as download
	 */
	function export($languageCode, $todir='', $zipfilename='', $directDownload=false) {

		$this->__initExport($languageCode);
		
		// Call language export function
		$this->export_Language($languageCode);

		$this->__finishExport();
		
		// Export as Zip
		if($zipfilename == '') $zipfilename = "$languageCode-" . date('YmdHis') . ".zip";
		$zipfilename = "$this->_export_tmpdir/$zipfilename";

		$zip = new Ncrm_Zip($zipfilename);

		// Add manifest file
		$zip->addFile($this->__getManifestFilePath(), "manifest.xml");

		// Copy module directory
		$zip->copyDirectoryFromDisk("languages/$languageCode", "modules");

		$zip->save();

		if($todir) {
			copy($zipfilename, $todir);
		}

		if($directDownload) {
			$zip->forceDownload($zipfilename);
			unlink($zipfilename);
		}
		$this->__cleanupExport();
	}
	
	/**
	 * Export Language Handler
	 * @access private
	 */
	function export_Language($prefix) {
		global $adb;

		$sqlresult = $adb->pquery("SELECT * FROM ncrm_language WHERE prefix = ?", array($prefix));
		$languageresultrow = $adb->fetch_array($sqlresult);

		$langname  = decode_html($languageresultrow['name']);
		$langlabel = decode_html($languageresultrow['label']);

		$this->openNode('module');
		$this->outputNode(date('Y-m-d H:i:s'),'exporttime');
		$this->outputNode($langname, 'name');
		$this->outputNode($langlabel, 'label');
		$this->outputNode($prefix, 'prefix');

		$this->outputNode('language', 'type');

		// Export dependency information
		$this->export_Dependencies($moduleInstance);

        $this->closeNode('module');
	}
	
	/**
	 * Export ncrm dependencies
	 * @access private
	 */
	function export_Dependencies($moduleInstance) {
		global $ncrm_current_version, $adb;

		$ncrmMinVersion = $ncrm_current_version;
		$ncrmMaxVersion = false;

		$this->openNode('dependencies');
		$this->outputNode($ncrmMinVersion, 'ncrm_version');
		if($ncrmMaxVersion !== false)	$this->outputNode($ncrmMaxVersion, 'ncrm_max_version');
		$this->closeNode('dependencies');
	}


	/**
	 * Initialize Language Schema
	 * @access private
	 */
	static function __initSchema() {
		$hastable = Ncrm_Utils::CheckTable(self::TABLENAME);
		if(!$hastable) {
			Ncrm_Utils::CreateTable(
				self::TABLENAME,
				'(id INT NOT NULL PRIMARY KEY,
				name VARCHAR(50), prefix VARCHAR(10), label VARCHAR(30), lastupdated DATETIME, sequence INT, isdefault INT(1), active INT(1))',
				true
			);
			global $languages, $adb;
			foreach($languages as $langkey=>$langlabel) {
				$uniqueid = self::__getUniqueId();
				$adb->pquery('INSERT INTO '.self::TABLENAME.'(id,name,prefix,label,lastupdated,active) VALUES(?,?,?,?,?,?)',
					Array($uniqueid, $langlabel,$langkey,$langlabel,date('Y-m-d H:i:s',time()), 1));
			}
		}
	}

	/**
	 * Register language pack information.
	 */
	static function register($prefix, $label, $name='', $isdefault=false, $isactive=true, $overrideCore=false) {
		self::__initSchema();

		$prefix = trim($prefix);
		// We will not allow registering core language unless forced
		if(strtolower($prefix) == 'en_us' && $overrideCore == false) return;

		$useisdefault = ($isdefault)? 1 : 0;
		$useisactive  = ($isactive)?  1 : 0;

		global $adb;
		$checkres = $adb->pquery('SELECT * FROM '.self::TABLENAME.' WHERE prefix=?', Array($prefix));
		$datetime = date('Y-m-d H:i:s');
		if($adb->num_rows($checkres)) {
			$id = $adb->query_result($checkres, 0, 'id');
			$adb->pquery('UPDATE '.self::TABLENAME.' set label=?, name=?, lastupdated=?, isdefault=?, active=? WHERE id=?',
				Array($label, $name, $datetime, $useisdefault, $useisactive, $id));
		} else {
			$uniqueid = self::__getUniqueId();
			$adb->pquery('INSERT INTO '.self::TABLENAME.' (id,name,prefix,label,lastupdated,isdefault,active) VALUES(?,?,?,?,?,?,?)',
				Array($uniqueid, $name, $prefix, $label, $datetime, $useisdefault, $useisactive));
		}
		self::log("Registering Language $label [$prefix] ... DONE");		
	}

	/**
	 * De-Register language pack information
	 * @param String Language prefix like (de_de) etc
	 */
	static function deregister($prefix) {
		$prefix = trim($prefix);
		// We will not allow deregistering core language
		if(strtolower($prefix) == 'en_us') return;

		self::__initSchema();

		global $adb;
		$checkres = $adb->pquery('DELETE FROM '.self::TABLENAME.' WHERE prefix=?', Array($prefix));
		self::log("Deregistering Language $prefix ... DONE");
	}

	/**
	 * Get all the language information
	 * @param Boolean true to include in-active languages also, false (default)
	 */
	static function getAll($includeInActive=false) {
		global $adb;
		$hastable = Ncrm_Utils::CheckTable(self::TABLENAME);

		$languageinfo = Array();

		if($hastable) {
			if($includeInActive) $result = $adb->pquery('SELECT * FROM '.self::TABLENAME, array());
			else $result = $adb->pquery('SELECT * FROM '.self::TABLENAME . ' WHERE active=?', array(1));

			for($index = 0; $index < $adb->num_rows($result); ++$index) {
				$resultrow = $adb->fetch_array($result);
				$prefix = $resultrow['prefix'];
				$label  = $resultrow['label'];
				$languageinfo[$prefix] = $label;
			}
		} else {
			global $languages;
			foreach($languages as $prefix=>$label) {
				$languageinfo[$prefix] = $label;
			}
		}
		return $languageinfo;
	}
}
?>
