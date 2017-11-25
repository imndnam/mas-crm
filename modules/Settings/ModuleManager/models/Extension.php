<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

vimport('~~/vtlib/Ncrm/Net/Client.php');
vimport('~~/vtlib/Ncrm/Package.php');

class Settings_ModuleManager_Extension_Model extends Ncrm_Base_Model {


	var $fileName;

	public static function getUploadDirectory($isChild = false) {
		$uploadDir .= 'test/vtlib';
		if ($isChild) {
			$uploadDir = '../'.$uploadDir;
		}
		return $uploadDir;
	}

	/**
	 * Function to set id for this instance
	 * @param <Integer> $extensionId
	 * @return <type>
	 */
	public function setId($extensionId) {
		$this->set('id', $extensionId);
		return $this;
	}

	/**
	 * Function to set file name for this instance
	 * @param <type> $fileName
	 * @return <type>
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
		return $this;
	}

	/**
	 * Function to get Id of this instance
	 * @return <Integer> id
	 */
	public function getId() {
		return $this->get('id');
	}

	/**
	 * Function to get name of this instance
	 * @return <String> module name
	 */
	public function getName() {
		return $this->get('name');
	}

	/**
	 * Function to get file name of this instance
	 * @return <String> file name
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * Function to get package of this instance
	 * @return <Ncrm_Package> package object
	 */
	public function getPackage() {
		$packageModel = new Ncrm_Package();
		$moduleName = $packageModel->getModuleNameFromZip(self::getUploadDirectory(). '/' .$this->getFileName());
		if ($moduleName) {
			return $packageModel;
		}
		return false;
	}

	/**
	 * Function to check whether it is compatible with ncrm or not
	 * @return <boolean> true/false
	 */
	public function isNcrmCompatible() {
		vimport('~~/vtlib/Ncrm/Version.php');
		$ncrmVersion = $this->get('ncrmVersion');
		$ncrmMaxVersion = $this->get('ncrmMaxVersion');

		if ((Ncrm_Version::check($ncrmVersion, '>=') && $ncrmMaxVersion && Ncrm_Version::check($ncrmMaxVersion, '<'))
				|| Ncrm_Version::check($ncrmVersion, '=')) {
			return true;
		}
		return false;
	}

	/**
	 * Function to check whether the module is already exists or not
	 * @return <true/false>
	 */
	public function isAlreadyExists() {
		$moduleName = $this->getName();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		if($moduleModel) {
			return true;
		}
		return false;
	}

	/**
	 * Function to check whether the module is upgradable or not
	 * @return <type>
	 */
	public function isUpgradable() {
		$moduleName = $this->getName();
		$moduleModel = Ncrm_Module_Model::getInstance($moduleName);
		if ($moduleModel) {
			if ($moduleModel->get('version') < $this->get('pkgVersion')) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Function to get instance by using XML node
	 * @param <XML DOM> $extensionXMLNode
	 * @return <Settings_ModuleManager_Extension_Model> $extensionModel
	 */
	public static function getInstanceFromXMLNodeObject($extensionXMLNode) {
		$extensionModel = new self();
		$objectProperties = get_object_vars($extensionXMLNode);

		foreach($objectProperties as $propertyName => $propertyValue) {
			$propertyValue = (string)$propertyValue;
			if ($propertyName === 'description') {
				$propertyValue = nl2br(str_replace(array('<','>'), array('&lt;', '&gt;'), br2nl(trim($propertyValue))));
			}
			$extensionModel->set($propertyName, $propertyValue);
		}

		$label = $extensionModel->get('label');
		if (!$label) {
			$extensionModel->set('label', $extensionModel->getName());
		}
		return $extensionModel;
	}

	/**
	 * Function to get instance by using id
	 * @param <Integer> $extensionId
	 * @param <String> $fileName
	 * @return <Settings_ModuleManager_Extension_Model> $extension Model
	 */
	public static function getInstanceById($extensionId, $fileName = false) {
		$uploadDir = self::getUploadDirectory();
		if ($fileName) {
			if (is_dir($uploadDir)) {
				$uploadFileName = "$uploadDir/$fileName";
				checkFileAccess(self::getUploadDirectory());

				$extensionModel = new self();
				$extensionModel->setId($extensionId)->setFileName($fileName);
				return $extensionModel;
			}
		} else {
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir);
			}
			$uploadFile = 'usermodule_'. time() . '.zip';
			$uploadFileName = "$uploadDir/$uploadFile";
			checkFileAccess(self::getUploadDirectory());

			$packageAvailable = Settings_ModuleManager_Extension_Model::download($extensionId, $uploadFileName);
			if ($packageAvailable) {
				$extensionModel = new self();
				$extensionModel->setId($extensionId)->setFileName($uploadFile);
				return $extensionModel;
			}
		}
		return false;
	}
        
	
     /**
	 * Function to download the file of this instance
	 * @param <Integer> $extensionId
	 * @param <String> $targetFileName
	 * @return <boolean> true/false
	 */
	public static function download($extensionId, $targetFileName) {
		$extensions = self::getAll();
		$downloadURL = $extensions[$extensionId]->get('downloadURL');

		if ($downloadURL) {
			$clientModel = new Ncrm_Net_Client($downloadURL);
			file_put_contents($targetFileName, $clientModel->doGet());
			return true;
		}
		return false;
	}
}
