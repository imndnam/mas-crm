<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Ncrm/ThemeExport.php');

/**
 * Provides API to import language into NCRM
 * @package vtlib
 */
class Ncrm_ThemeImport extends Ncrm_ThemeExport {
    
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
		$this->_export_tmpdir;
	}
        
	/**
	 * Initialize Import
	 * @access private
	 */
	function initImport($zipfile, $overwrite) {
		$this->__initSchema();
		$name = $this->getModuleNameFromZip($zipfile);
                return $name;
	}

	/**
	 * Import Module from zip file
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function import($zipfile, $overwrite=false) {
		$this->initImport($zipfile, $overwrite);

		// Call module import function
		$this->import_Theme($zipfile);
	}

	/**
	 * Update Module from zip file
	 * @param Object Instance of Language (to keep Module update API consistent)
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function update($instance, $zipfile, $overwrite=true) {
		$this->import($zipfile, $overwrite);
	}

	/**
	 * Import Module
	 * @access private
	 */
	function import_Theme($zipfile) {
		$name = $this->_modulexml->name;
		$label = $this->_modulexml->label;
                $parent = $this->_modulexml->parent;

		self::log("Importing $label ... STARTED");
		$unzip = new Ncrm_Unzip($zipfile);
		$filelist = $unzip->getList();
		$ncrm6format = false;
                
		foreach($filelist as $filename=>$fileinfo) {
			if(!$unzip->isdir($filename)) {

				if(strpos($filename, '/') === false) continue;


				$targetdir  = substr($filename, 0, strripos($filename,'/'));
				$targetfile = basename($filename);
                                $dounzip = false;
                                // Case handling for jscalendar
                                if(stripos($targetdir, "layouts/$parent/skins/$label") === 0) {
                                    $dounzip = true;
                                    $ncrm6format = true;
                                }
				if($dounzip) {
					// ncrm6 format
					if ($ncrm6format) {
                                               $targetdir = "layouts/$parent/skins/" . str_replace("layouts/$parent/skins", "", $targetdir);
						@mkdir($targetdir, 0777, true);
					}

					if($unzip->unzip($filename, "$targetdir/$targetfile") !== false) {
						self::log("Copying file $filename ... DONE");
					} else {
						self::log("Copying file $filename ... FAILED");
					}
				} else {
					self::log("Copying file $filename ... SKIPPED");
				}
			}
		}
		if($unzip) $unzip->close();

		self::register($label, $name, $parent);

		self::log("Importing $label [$prefix] ... DONE");

		return;
	}    
}