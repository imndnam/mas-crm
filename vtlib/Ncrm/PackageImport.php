<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Ncrm/PackageExport.php');
include_once('vtlib/Ncrm/Unzip.php');
include_once('vtlib/Ncrm/Module.php');
include_once('vtlib/Ncrm/Event.php');
include_once('vtlib/Ncrm/Cron.php');

/**
 * Provides API to import module into NCRM
 * @package vtlib
 */
class Ncrm_PackageImport extends Ncrm_PackageExport {

	/**
	 * Module Meta XML File (Parsed)
	 * @access private
	 */
	var $_modulexml;
	/**
	 * Module Fields mapped by [modulename][fieldname] which
	 * will be used to create customviews.
	 * @access private
	 */
	var $_modulefields_cache = Array();

	/**
	 * License of the package.
	 * @access private
	 */
	var $_licensetext = false;

	/**
	 * Constructor
	 */
	function Ncrm_PackageImport() {
		parent::__construct();
	}

	/**
	 * Parse the manifest file
	 * @access private
	 */
	function __parseManifestFile($unzip) {
		$manifestfile = $this->__getManifestFilePath();
		$unzip->unzip('manifest.xml', $manifestfile);
		$this->_modulexml = simplexml_load_file($manifestfile);
		unlink($manifestfile);
	}

	/**
	 * Get type of package (as specified in manifest)
	 */
	function type() {
		if(!empty($this->_modulexml) && !empty($this->_modulexml->type)) {
			return $this->_modulexml->type;
		}
		return false;
	}

	/**
	 * XPath evaluation on the root module node.
	 * @param String Path expression
	 */
	function xpath($path) {
		return $this->_modulexml->xpath($path);
	}

	/**
	 * Get the value of matching path (instead of complete xpath result)
	 * @param String Path expression for which value is required
	 */
	function xpath_value($path) {
		$xpathres = $this->xpath($path);
		foreach($xpathres as $pathkey=>$pathvalue) {
			if($pathkey == $path) return $pathvalue;
		}
		return false;
	}

	/**
	 * Are we trying to import layout package?
	 */
	function isLanguageType($zipfile =null) {
		if(!empty($zipfile)) {
			if(!$this->checkZip($zipfile)) {
				return false;
			}
		}
		$packagetype = $this->type();

		if($packagetype) {
			$lcasetype = strtolower($packagetype);
			if($lcasetype == 'language') return true;
		}
                if($packagetype) {
			$lcasetype = strtolower($packagetype);
			if($lcasetype == 'layout') return true;
		}
		return false;
	}
        
        /**
	 * Are we trying to import extension package?
	 */
	function isExtensionType($zipfile =null) {
		if(!empty($zipfile)) {
			if(!$this->checkZip($zipfile)) {
				return false;
			}
		}
		$packagetype = $this->type();

                if($packagetype) {
			$lcasetype = strtolower($packagetype);
			if($lcasetype == 'extension') return true;
		}
		return false;
	}
        
        /**
	 * Are we trying to import language package?
	 */
	function isLayoutType($zipfile =null) {
		if(!empty($zipfile)) {
			if(!$this->checkZip($zipfile)) {
				return false;
			}
		}
		$packagetype = $this->type();

                if($packagetype) {
			$lcasetype = strtolower($packagetype);
			if($lcasetype == 'layout') return true;
		}
		return false;
	}

	/**
	 * checks whether a package is module bundle or not.
	 * @param String $zipfile - path to the zip file.
	 * @return Boolean - true if given zipfile is a module bundle and false otherwise.
	 */
	function isModuleBundle($zipfile = null) {
		// If data is not yet available
		if(!empty($zipfile)) {
			if(!$this->checkZip($zipfile)) {
				return false;
			}
		}

		return (boolean)$this->_modulexml->modulebundle;
	}

	/**
	 * @return Array module list available in the module bundle.
	 */
	function getAvailableModuleInfoFromModuleBundle() {
		$list = (Array)$this->_modulexml->modulelist;
		return (Array)$list['dependent_module'];
	}

	/**
	 * Get the license of this package
	 * NOTE: checkzip should have been called earlier.
	 */
	function getLicense() {
		return $this->_licensetext;
	}

	/**
	 * Check if zipfile is a valid package
	 * @access private
	 */
	function checkZip($zipfile) {
		$unzip = new Ncrm_Unzip($zipfile);
		$filelist = $unzip->getList();

		$manifestxml_found = false;
		$languagefile_found = false;
                $layoutfile_found = false;
		$ncrmversion_found = false;

		$modulename = null;
		$language_modulename = null;

                foreach($filelist as $filename=>$fileinfo) {
			$matches = Array();
			preg_match('/manifest.xml/', $filename, $matches);
			if(count($matches)) {
				$manifestxml_found = true;
				$this->__parseManifestFile($unzip);
				$modulename = $this->_modulexml->name;
				$isModuleBundle = (string)$this->_modulexml->modulebundle;

				if($isModuleBundle === 'true' && (!empty($this->_modulexml)) &&
						(!empty($this->_modulexml->dependencies)) &&
						(!empty($this->_modulexml->dependencies->ncrm_version))) {
					$languagefile_found = true;
					break;
				}

				// Do we need to check the zip further?
				if($this->isLanguageType()) {
					$languagefile_found = true; // No need to search for module language file.
					break;
				}else if($this->isLayoutType()){
                                    $layoutfile_found = true; // No need to search for module language file.
                                    break;
                                }else if($this->isExtensionType()){
                                    $extensionfile_found = true; // No need to search for module language file.
                                    break;
                                }else {
					continue;
				}
			}
			// Language file present in en_us folder
			$pattern = '/languages\/en_us\/([^\/]+).php/';
			preg_match($pattern, $filename, $matches);
			if(count($matches)) { $language_modulename = $matches[1]; }

			// or Language file may be present in en_us/Settings folder
			$settingsPattern = '/languages\/en_us\/Settings\/([^\/]+).php/';
			preg_match($settingsPattern, $filename, $matches);
			if(count($matches)) { $language_modulename = $matches[1]; }
		}

                // Verify module language file.
		if(!empty($language_modulename) && $language_modulename == $modulename) {
			$languagefile_found = true;
		}

		if(!empty($this->_modulexml) &&
			!empty($this->_modulexml->dependencies) &&
			!empty($this->_modulexml->dependencies->ncrm_version)) {
				$ncrmVersion = (string)$this->_modulexml->dependencies->ncrm_version;
				if(version_compare($ncrmVersion, '6.0.0rc', '>=') === true) {
					$ncrmversion_found = true;
				}
		}

		$validzip = false;
		if($manifestxml_found && $languagefile_found && $ncrmversion_found)
			$validzip = true;

                if($manifestxml_found && $layoutfile_found && $ncrmversion_found)
			$validzip = true;
                
                if($manifestxml_found && $extensionfile_found && $ncrmversion_found)
			$validzip = true;
                
		if($validzip) {
			if(!empty($this->_modulexml->license)) {
				if(!empty($this->_modulexml->license->inline)) {
					$this->_licensetext = $this->_modulexml->license->inline;
				} else if(!empty($this->_modulexml->license->file)) {
					$licensefile = $this->_modulexml->license->file;
					$licensefile = "$licensefile";
					if(!empty($filelist[$licensefile])) {
						$this->_licensetext = $unzip->unzip($licensefile);
					} else {
						$this->_licensetext = "Missing $licensefile!";
					}
				}
			}
		}

		if($unzip) $unzip->close();

		return $validzip;
	}

	/**
	 * Get module name packaged in the zip file
	 * @access private
	 */
	function getModuleNameFromZip($zipfile) {
		if(!$this->checkZip($zipfile)) return null;

                return (string)$this->_modulexml->name;
	}

	/**
	 * returns the name of the module.
	 * @return String - name of the module as given in manifest file.
	 */
	function getModuleName() {
		return (string)$this->_modulexml->name;
	}

	/**
	 * Cache the field instance for re-use
	 * @access private
	 */
	function __AddModuleFieldToCache($moduleInstance, $fieldname, $fieldInstance) {
		$this->_modulefields_cache["$moduleInstance->name"]["$fieldname"] = $fieldInstance;
	}

	/**
	 * Get field instance from cache
	 * @access private
	 */
	function __GetModuleFieldFromCache($moduleInstance, $fieldname) {
		return $this->_modulefields_cache["$moduleInstance->name"]["$fieldname"];
	}

	/**
	 * Initialize Import
	 * @access private
	 */
	function initImport($zipfile, $overwrite=true) {
		$module = $this->getModuleNameFromZip($zipfile);
		if($module != null) {

			$unzip = new Ncrm_Unzip($zipfile, $overwrite);

			// Unzip selectively
			$unzip->unzipAllEx( ".",
				Array(
					// Include only file/folders that need to be extracted
					'include' => Array('templates', "modules/$module", 'cron', 'languages',
						'settings/actions', 'settings/views', 'settings/models', 'settings/templates', 'settings/connectors', 'settings/libraries',
						"$module.png", 'layouts'),
					// NOTE: If excludes is not given then by those not mentioned in include are ignored.
				),
				// What files needs to be renamed?
				Array(
					// Templates folder
					'templates' => "layouts/vlayout/modules/$module",
					// Cron folder
					'cron' => "cron/modules/$module",
					// Settings folder
					'settings/actions' => "modules/Settings/$module/actions",
					'settings/views' => "modules/Settings/$module/views",
					'settings/models' => "modules/Settings/$module/models",
                                        'settings/connectors' => "modules/Settings/$module/connectors",
                                        'settings/libraries' => "modules/Settings/$module/libraries",
					// Settings templates folder
					'settings/templates' => "layouts/vlayout/modules/Settings/$module",
                                        //module images
					'images' => "layouts/vlayout/skins/images/$module",
                                        'settings' => "modules/Settings",
                                        'layouts' => 'layouts' 
				)
			);

			if($unzip->checkFileExistsInRootFolder("$module.png")) {
				$unzip->unzip("$module.png", "layouts/vlayout/skins/images/$module.png");
			}

			if($unzip) $unzip->close();
		}
		return $module;
	}

	function getTemporaryFilePath($filepath=false) {
		return 'cache/'. $filepath;
	}
	/**
	 * Get dependent version
	 * @access private
	 */
	function getDependentNcrmVersion() {
		return $this->_modulexml->dependencies->ncrm_version;
	}

	/**
	 * Get dependent Maximum version
	 * @access private
	 */
	function getDependentMaxNcrmVersion() {
		return $this->_modulexml->dependencies->ncrm_max_version;
	}

	/**
	 * Get package version
	 * @access private
	 */
	function getVersion() {
		return $this->_modulexml->version;
	}
        
        /**
	 * Get package author name
	 * @access private
	 */
	function getAuthorName() {
		return $this->_modulexml->authorname;
	}

        /**
	 * Get package author phone number
	 * @access private
	 */
	function getAuthorPhone() {
		return $this->_modulexml->authorphone;
	}
        
        /**
	 * Get package author phone email
	 * @access private
	 */
	function getAuthorEmail() {
		return $this->_modulexml->authoremail;
	}
        
        /**
	 * Get package author phone email
	 * @access private
	 */
	function getDescription() {
		return $this->_modulexml->description;
	}
        
        
	/**
	 * Import Module from zip file
	 * @param String Zip file name
	 * @param Boolean True for overwriting existing module
	 *
	 * @todo overwrite feature is not functionally currently.
	 */
	function import($zipfile, $overwrite=false) {
		$module = $this->getModuleNameFromZip($zipfile);
		if($module != null) {
			// If data is not yet available
			if(empty($this->_modulexml)) {
				$this->__parseManifestFile($unzip);
			}

			$buildModuleArray = array();
			$installSequenceArray = array();
			$moduleBundle = (boolean)$this->_modulexml->modulebundle;
			if($moduleBundle == true) {
				$moduleList = (Array)$this->_modulexml->modulelist;
				foreach($moduleList as $moduleInfos) {
					foreach($moduleInfos as $moduleInfo) {
						$moduleInfo = (Array)$moduleInfo;
						$buildModuleArray[] = $moduleInfo;
						$installSequenceArray[] = $moduleInfo['install_sequence'];
					}
				}
				sort($installSequenceArray);
				$unzip = new Ncrm_Unzip($zipfile);
				$unzip->unzipAllEx($this->getTemporaryFilePath());
				foreach ($installSequenceArray as $sequence) {
					foreach ($buildModuleArray as $moduleInfo) {
						if($moduleInfo['install_sequence'] == $sequence) {
							$this->import($this->getTemporaryFilePath($moduleInfo['filepath']), $overwrite);
						}
					}
				}
			} else {
				$module = $this->initImport($zipfile, $overwrite);
				// Call module import function
				$this->import_Module();
			}
		}
	}


	/**
	 * Import Module
	 * @access private
	 */
	function import_Module() {
		$tabname = $this->_modulexml->name;
		$tablabel= $this->_modulexml->label;
		$parenttab=(string)$this->_modulexml->parent;
		$tabversion=$this->_modulexml->version;

		$isextension= false;
		if(!empty($this->_modulexml->type)) {
			$type = strtolower($this->_modulexml->type);
			if($type == 'extension' || $type == 'language')
				$isextension = true;
		}

		$ncrmMinVersion = $this->_modulexml->dependencies->ncrm_version;
		$ncrmMaxVersion = $this->_modulexml->dependencies->ncrm_max_version;

		$moduleInstance = new Ncrm_Module();
		$moduleInstance->name = $tabname;
		$moduleInstance->label= $tablabel;
        $moduleInstance->parent=$parenttab;
		$moduleInstance->isentitytype = ($isextension != true);
		$moduleInstance->version = (!$tabversion)? 0 : $tabversion;
		$moduleInstance->minversion = (!$ncrmMinVersion)? false : $ncrmMinVersion;
		$moduleInstance->maxversion = (!$ncrmMaxVersion)?  false : $ncrmMaxVersion;
		$moduleInstance->save();

		if(!empty($parenttab)) {
			$menuInstance = Ncrm_Menu::getInstance($parenttab);
			$menuInstance->addModule($moduleInstance);
		}

		$this->import_Tables($this->_modulexml);
		$this->import_Blocks($this->_modulexml, $moduleInstance);
		$this->import_CustomViews($this->_modulexml, $moduleInstance);
		$this->import_SharingAccess($this->_modulexml, $moduleInstance);
		$this->import_Events($this->_modulexml, $moduleInstance);
		$this->import_Actions($this->_modulexml, $moduleInstance);
		$this->import_RelatedLists($this->_modulexml, $moduleInstance);
		$this->import_CustomLinks($this->_modulexml, $moduleInstance);
		$this->import_CronTasks($this->_modulexml);

		Ncrm_Module::fireEvent($moduleInstance->name,
			Ncrm_Module::EVENT_MODULE_POSTINSTALL);

		$moduleInstance->initWebservice();
	}

	/**
	 * Import Tables of the module
	 * @access private
	 */
	function import_Tables($modulenode) {
		if(empty($modulenode->tables) || empty($modulenode->tables->table)) return;

		/**
		 * Record the changes in schema file
		 */
                
        if(file_exists("modules/$modulenode->name")){
            $fileToOpen = "modules/$modulenode->name/schema.xml";
        } else if(file_exists("modules/Settings/$modulenode->name")){
            $fileToOpen = "modules/Settings/$modulenode->name/schema.xml";
        }
		$schemafile = fopen($fileToOpen, 'w');
		if($schemafile) {
			fwrite($schemafile, "<?xml version='1.0'?>\n");
			fwrite($schemafile, "<schema>\n");
			fwrite($schemafile, "\t<tables>\n");
		}

		// Import the table via queries
		foreach($modulenode->tables->table as $tablenode) {
			$tablename = $tablenode->name;
			$tablesql  = "$tablenode->sql"; // Convert to string format

			// Save the information in the schema file.
			fwrite($schemafile, "\t\t<table>\n");
			fwrite($schemafile, "\t\t\t<name>$tablename</name>\n");
			fwrite($schemafile, "\t\t\t<sql><![CDATA[$tablesql]]></sql>\n");
			fwrite($schemafile, "\t\t</table>\n");

			// Avoid executing SQL that will DELETE or DROP table data
			if(Ncrm_Utils::IsCreateSql($tablesql)) {
				if(!Ncrm_Utils::checkTable($tablename)) {
					self::log("SQL: $tablesql ... ", false);
					Ncrm_Utils::ExecuteQuery($tablesql);
					self::log("DONE");
				}
			} else {
				if(Ncrm_Utils::IsDestructiveSql($tablesql)) {
					self::log("SQL: $tablesql ... SKIPPED");
				} else {
					self::log("SQL: $tablesql ... ", false);
					Ncrm_Utils::ExecuteQuery($tablesql);
					self::log("DONE");
				}
			}
		}
		if($schemafile) {
			fwrite($schemafile, "\t</tables>\n");
			fwrite($schemafile, "</schema>\n");
			fclose($schemafile);
		}
	}

	/**
	 * Import Blocks of the module
	 * @access private
	 */
	function import_Blocks($modulenode, $moduleInstance) {
		if(empty($modulenode->blocks) || empty($modulenode->blocks->block)) return;
		foreach($modulenode->blocks->block as $blocknode) {
			$blockInstance = $this->import_Block($modulenode, $moduleInstance, $blocknode);
			$this->import_Fields($blocknode, $blockInstance, $moduleInstance);
		}
	}

	/**
	 * Import Block of the module
	 * @access private
	 */
	function import_Block($modulenode, $moduleInstance, $blocknode) {
		$blocklabel = $blocknode->label;

		$blockInstance = new Ncrm_Block();
		$blockInstance->label = $blocklabel;
		$moduleInstance->addBlock($blockInstance);
		return $blockInstance;
	}

	/**
	 * Import Fields of the module
	 * @access private
	 */
	function import_Fields($blocknode, $blockInstance, $moduleInstance) {
		if(empty($blocknode->fields) || empty($blocknode->fields->field)) return;

		foreach($blocknode->fields->field as $fieldnode) {
			$fieldInstance = $this->import_Field($blocknode, $blockInstance, $moduleInstance, $fieldnode);
		}
	}

	/**
	 * Import Field of the module
	 * @access private
	 */
	function import_Field($blocknode, $blockInstance, $moduleInstance, $fieldnode) {
		$fieldInstance = new Ncrm_Field();
		$fieldInstance->name         = $fieldnode->fieldname;
		$fieldInstance->label        = $fieldnode->fieldlabel;
		$fieldInstance->table        = $fieldnode->tablename;
		$fieldInstance->column       = $fieldnode->columnname;
		$fieldInstance->uitype       = $fieldnode->uitype;
		$fieldInstance->generatedtype= $fieldnode->generatedtype;
		$fieldInstance->readonly     = $fieldnode->readonly;
		$fieldInstance->presence     = $fieldnode->presence;
		$fieldInstance->defaultvalue = $fieldnode->defaultvalue;
		$fieldInstance->maximumlength= $fieldnode->maximumlength;
		$fieldInstance->sequence     = $fieldnode->sequence;
		$fieldInstance->quickcreate  = $fieldnode->quickcreate;
		$fieldInstance->quicksequence= $fieldnode->quickcreatesequence;
		$fieldInstance->typeofdata   = $fieldnode->typeofdata;
		$fieldInstance->displaytype  = $fieldnode->displaytype;
		$fieldInstance->info_type    = $fieldnode->info_type;

		if(!empty($fieldnode->helpinfo))
			$fieldInstance->helpinfo = $fieldnode->helpinfo;

		if(isset($fieldnode->masseditable))
			$fieldInstance->masseditable = $fieldnode->masseditable;

		if(isset($fieldnode->columntype) && !empty($fieldnode->columntype))
			$fieldInstance->columntype = $fieldnode->columntype;

		$blockInstance->addField($fieldInstance);

		// Set the field as entity identifier if marked.
		if(!empty($fieldnode->entityidentifier)) {
			$moduleInstance->entityidfield = $fieldnode->entityidentifier->entityidfield;
			$moduleInstance->entityidcolumn= $fieldnode->entityidentifier->entityidcolumn;
			$moduleInstance->setEntityIdentifier($fieldInstance);
		}

		// Check picklist values associated with field if any.
		if(!empty($fieldnode->picklistvalues) && !empty($fieldnode->picklistvalues->picklistvalue)) {
			$picklistvalues = Array();
			foreach($fieldnode->picklistvalues->picklistvalue as $picklistvaluenode) {
				$picklistvalues[] = $picklistvaluenode;
			}
			$fieldInstance->setPicklistValues( $picklistvalues );
		}

		// Check related modules associated with this field
		if(!empty($fieldnode->relatedmodules) && !empty($fieldnode->relatedmodules->relatedmodule)) {
			$relatedmodules = Array();
			foreach($fieldnode->relatedmodules->relatedmodule as $relatedmodulenode) {
				$relatedmodules[] = $relatedmodulenode;
			}
			$fieldInstance->setRelatedModules($relatedmodules);
		}
                
                // Set summary field if marked in xml
                if(!empty($fieldnode->summaryfield)) {
                    $fieldInstance->setSummaryField($fieldnode->summaryfield);
                }
                
		$this->__AddModuleFieldToCache($moduleInstance, $fieldnode->fieldname, $fieldInstance);
		return $fieldInstance;
	}

	/**
	 * Import Custom views of the module
	 * @access private
	 */
	function import_CustomViews($modulenode, $moduleInstance) {
		if(empty($modulenode->customviews) || empty($modulenode->customviews->customview)) return;
		foreach($modulenode->customviews->customview as $customviewnode) {
			$filterInstance = $this->import_CustomView($modulenode, $moduleInstance, $customviewnode);

		}
	}

	/**
	 * Import Custom View of the module
	 * @access private
	 */
	function import_CustomView($modulenode, $moduleInstance, $customviewnode) {
		$viewname = $customviewnode->viewname;
		$setdefault=$customviewnode->setdefault;
		$setmetrics=$customviewnode->setmetrics;

		$filterInstance = new Ncrm_Filter();
		$filterInstance->name = $viewname;
		$filterInstance->isdefault = $setdefault;
		$filterInstance->inmetrics = $setmetrics;

		$moduleInstance->addFilter($filterInstance);

		foreach($customviewnode->fields->field as $fieldnode) {
			$fieldInstance = $this->__GetModuleFieldFromCache($moduleInstance, $fieldnode->fieldname);
			$filterInstance->addField($fieldInstance, $fieldnode->columnindex);

			if(!empty($fieldnode->rules->rule)) {
				foreach($fieldnode->rules->rule as $rulenode) {
					$filterInstance->addRule($fieldInstance, $rulenode->comparator, $rulenode->value, $rulenode->columnindex);
				}
			}
		}
	}

	/**
	 * Import Sharing Access of the module
	 * @access private
	 */
	function import_SharingAccess($modulenode, $moduleInstance) {
		if(empty($modulenode->sharingaccess)) return;

		if(!empty($modulenode->sharingaccess->default)) {
			foreach($modulenode->sharingaccess->default as $defaultnode) {
				$moduleInstance->setDefaultSharing($defaultnode);
			}
		}
	}

	/**
	 * Import Events of the module
	 * @access private
	 */
	function import_Events($modulenode, $moduleInstance) {
		if(empty($modulenode->events) || empty($modulenode->events->event))	return;

		if(Ncrm_Event::hasSupport()) {
			foreach($modulenode->events->event as $eventnode) {
				$this->import_Event($modulenode, $moduleInstance, $eventnode);
			}
		}
	}

	/**
	 * Import Event of the module
	 * @access private
	 */
	function import_Event($modulenode, $moduleInstance, $eventnode) {
		$event_condition = '';
		$event_dependent = '[]';
		if(!empty($eventnode->condition)) $event_condition = "$eventnode->condition";
		if(!empty($eventnode->dependent)) $event_dependent = "$eventnode->dependent";
		Ncrm_Event::register($moduleInstance,
			(string)$eventnode->eventname, (string)$eventnode->classname,
			(string)$eventnode->filename, (string)$event_condition, (string)$event_dependent
		);
	}

	/**
	 * Import actions of the module
	 * @access private
	 */
	function import_Actions($modulenode, $moduleInstance) {
		if(empty($modulenode->actions) || empty($modulenode->actions->action)) return;
		foreach($modulenode->actions->action as $actionnode) {
			$this->import_Action($modulenode, $moduleInstance, $actionnode);
		}
	}

	/**
	 * Import action of the module
	 * @access private
	 */
	function import_Action($modulenode, $moduleInstance, $actionnode) {
		$actionstatus = $actionnode->status;
		if($actionstatus == 'enabled')
			$moduleInstance->enableTools($actionnode->name);
		else
			$moduleInstance->disableTools($actionnode->name);
	}

	/**
	 * Import related lists of the module
	 * @access private
	 */
	function import_RelatedLists($modulenode, $moduleInstance) {
		if(empty($modulenode->relatedlists) || empty($modulenode->relatedlists->relatedlist)) return;
		foreach($modulenode->relatedlists->relatedlist as $relatedlistnode) {
			$relModuleInstance = $this->import_Relatedlist($modulenode, $moduleInstance, $relatedlistnode);
		}
	}

	/**
	 * Import related list of the module.
	 * @access private
	 */
	function import_Relatedlist($modulenode, $moduleInstance, $relatedlistnode) {
		$relModuleInstance = Ncrm_Module::getInstance($relatedlistnode->relatedmodule);
		$label = $relatedlistnode->label;
		$actions = false;
		if(!empty($relatedlistnode->actions) && !empty($relatedlistnode->actions->action)) {
			$actions = Array();
			foreach($relatedlistnode->actions->action as $actionnode) {
				$actions[] = "$actionnode";
			}
		}
		if($relModuleInstance) {
			$moduleInstance->setRelatedList($relModuleInstance, "$label", $actions, "$relatedlistnode->function");
		}
		return $relModuleInstance;
	}

	/**
	 * Import custom links of the module.
	 * @access private
	 */
	function import_CustomLinks($modulenode, $moduleInstance) {
		if(empty($modulenode->customlinks) || empty($modulenode->customlinks->customlink)) return;

		foreach($modulenode->customlinks->customlink as $customlinknode) {
			$handlerInfo = null;
			if(!empty($customlinknode->handler_path)) {
				$handlerInfo = array();
				$handlerInfo = array("$customlinknode->handler_path",
										"$customlinknode->handler_class",
										"$customlinknode->handler");
			}
			$moduleInstance->addLink(
				"$customlinknode->linktype",
				"$customlinknode->linklabel",
				"$customlinknode->linkurl",
				"$customlinknode->linkicon",
				"$customlinknode->sequence",
				$handlerInfo
			);
		}
	}

	/**
	 * Import cron jobs of the module.
	 * @access private
	 */
	function import_CronTasks($modulenode){
		if(empty($modulenode->crons) || empty($modulenode->crons->cron)) return;
		foreach ($modulenode->crons->cron as $cronTask){
			if(empty($cronTask->status)){
                            $cronTask->status = Ncrm_Cron::$STATUS_DISABLED;
                        } else {
                            $cronTask->status = Ncrm_Cron::$STATUS_ENABLED;
                        } 
			if((empty($cronTask->sequence))){
				$cronTask->sequence=Ncrm_Cron::nextSequence();
			}
			Ncrm_Cron::register("$cronTask->name","$cronTask->handler", "$cronTask->frequency", "$modulenode->name","$cronTask->status","$cronTask->sequence","$cronTask->description");
		}
	}
}
?>
