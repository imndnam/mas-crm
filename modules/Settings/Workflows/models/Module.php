<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

require_once 'modules/com_ncrm_workflow/include.inc';
require_once 'modules/com_ncrm_workflow/expression_engine/VTExpressionsManager.inc';

class Settings_Workflows_Module_Model extends Settings_Ncrm_Module_Model {

	var $baseTable = 'com_ncrm_workflows';
	var $baseIndex = 'workflow_id';
	var $listFields = array('summary' => 'Summary', 'module_name' => 'Module', 'execution_condition' => 'Execution Condition');
	var $name = 'Workflows';

	static $metaVariables = array(
		'Current Date' => '(general : (__NcrmMeta__) date) ($_DATE_FORMAT_)',
		'Current Time' => '(general : (__NcrmMeta__) time)',
		'System Timezone' => '(general : (__NcrmMeta__) dbtimezone)',
		'User Timezone' => '(general : (__NcrmMeta__) usertimezone)',
		'CRM Detail View URL' => '(general : (__NcrmMeta__) crmdetailviewurl)',
		'Portal Detail View URL' => '(general : (__NcrmMeta__) portaldetailviewurl)',
		'Site Url' => '(general : (__NcrmMeta__) siteurl)',
		'Portal Url' => '(general : (__NcrmMeta__) portalurl)',
		'Record Id' => '(general : (__NcrmMeta__) recordId)',
		'LBL_HELPDESK_SUPPORT_NAME' => '(general : (__NcrmMeta__) supportName)',
		'LBL_HELPDESK_SUPPORT_EMAILID' => '(general : (__NcrmMeta__) supportEmailid)',
	);

	static $triggerTypes = array(
		1 => 'ON_FIRST_SAVE',
		2 => 'ONCE',
		3 => 'ON_EVERY_SAVE',
		4 => 'ON_MODIFY',
        // Reserving 5 & 6 for ON_DELETE and ON_SCHEDULED types.
		6=>	 'ON_SCHEDULE'
	);

	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public static function getDefaultUrl() {
		return 'index.php?module=Workflows&parent=Settings&view=List';
	}

	/**
	 * Function to get the url for create view of the module
	 * @return <string> - url
	 */
	public static function getCreateViewUrl() {
		return "javascript:Settings_Workflows_List_Js.triggerCreate('index.php?module=Workflows&parent=Settings&view=Edit')";
	}

	public static function getCreateRecordUrl() {
		return 'index.php?module=Workflows&parent=Settings&view=Edit';
	}

	public static function getSupportedModules() {
		$moduleModels = Ncrm_Module_Model::getAll(array(0,2));
		$supportedModuleModels = array();
		foreach($moduleModels as $tabId => $moduleModel) {
			if($moduleModel->isWorkflowSupported() && $moduleModel->getName() != 'Webmails') {
				$supportedModuleModels[$tabId] = $moduleModel;
			}
		}
		return $supportedModuleModels;
	}

	public static function getTriggerTypes() {
		return self::$triggerTypes;
	}

	public static function getExpressions() {
		$db = PearDatabase::getInstance();

		$mem = new VTExpressionsManager($db);
		return $mem->expressionFunctions();
	}

	public static function getMetaVariables() {
		return self::$metaVariables;
	}

	public function getListFields() {
		if(!$this->listFieldModels) {
			$fields = $this->listFields;
			$fieldObjects = array();
			foreach($fields as $fieldName => $fieldLabel) {
				if($fieldName == 'module_name' || $fieldName == 'execution_condition') {
					$fieldObjects[$fieldName] = new Ncrm_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel, 'sort'=>false));
				} else {
					$fieldObjects[$fieldName] = new Ncrm_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel));
				}
			}
			$this->listFieldModels = $fieldObjects;
		}
		return $this->listFieldModels;
	}
        
        /**
     * Function to get the count of active workflows
     * @return <Integer> count of active workflows
     */
    public function getActiveWorkflowCount(){
        $db = PearDatabase::getInstance();

		$query = 'SELECT count(*) AS count FROM com_ncrm_workflows 
                  INNER JOIN ncrm_tab ON ncrm_tab.name = com_ncrm_workflows.module_name 
                  AND ncrm_tab.presence IN (0,2)';

		$result = $db->pquery($query, array());
		return $db->query_result($result, 0, 'count');
    }      
}
