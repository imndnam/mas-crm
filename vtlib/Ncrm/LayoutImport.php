<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Ncrm/LayoutExport.php');

/**
 * Provides API to import layout into NCRM
 * @package vtlib
 */
class Ncrm_LayoutImport extends Ncrm_LayoutExport {
    
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
		$this->import_Layout($zipfile);
	}

	/**
	 * Update Layout from zip file
	 * @param Object Instance of Layout
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 */
	function update($instance, $zipfile, $overwrite=true) {
		$this->import($zipfile, $overwrite);
	}

	/**
	 * Import Layout
	 * @access private
	 */
function import_Layout($zipfile) {
		$name = $this->_modulexml->name;
		$label = $this->_modulexml->label;
       
		self::log("Importing $name ... STARTED");
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
                                if(stripos($targetdir, "layouts/$name/skins") === 0) {
                                    $dounzip = true;
                                    $ncrm6format = true;
                                }
                                // ncrm6 format
                                else if (stripos($targetdir, "layouts/$name/modules") === 0) {
                                    $ncrm6format = true;
                                    $dounzip = true;
                                }
                                //case handling for the  special library files
                                else if (stripos($targetdir, "layouts/$name/libraries") === 0) {
                                    $ncrm6format = true;
                                    $dounzip = true;
                                }
				if($dounzip) {
					// ncrm6 format
					if ($ncrm6format) {
                     $targetdir = "layouts/$name/" . str_replace("layouts/$name", "", $targetdir);
						@mkdir($targetdir, 0755, true);
					}
    
                    global $upload_badext;
                    $badFileExtensions= array_diff($upload_badext, array('js'));
                    $filepath = 'zip://'.$zipfile.'#'.$filename ;
                    $fileValidation = Ncrm_Functions::verifyClaimedMIME($filepath, $badFileExtensions);
         
                    $imageContents = file_get_contents('zip://'.$zipfile.'#'.$filename); 
                    // Check for php code injection
                    if (preg_match('/(<\?php?(.*?))/i', $imageContents) == 1) {
                    $fileValidation = false;
                    }
                    
                   if($fileValidation){
                           if($unzip->unzip($filename, "$targetdir/$targetfile") !== false){
                                 self::log("Copying file $filename ... DONE");
                             } else {
                                 self::log("Copying file $filename ... FAILED");
                             } 
                     }
				    } else {
					self::log("Copying file $filename ... SKIPPED");
				}
			}
		}
		if($unzip) $unzip->close();

		self::register($name,$label);

		self::log("Importing $name($label) ... DONE");

		return;
	}
   
   
}