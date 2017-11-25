<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class PBXManager_Module_Model extends Ncrm_Module_Model {

	/**
	 * Function to check whether the module is an entity type module or not
	 * @return <Boolean> true/false
	 */
	public function isQuickCreateSupported() {
		//PBXManager module is not enabled for quick create
		return false;
	}

	public function isWorkflowSupported() {
		return true;
	}
    
    /**
	 * Overided to make editview=false for this module
	 */
	public function isPermitted($actionName) {
        if($actionName == 'EditView')
            return false;
        else
            return ($this->isActive() && Users_Privileges_Model::isPermitted($this->getName(), $actionName));
	}
    
    /**
	 * Function to get Settings links
	 * @return <Array>
	 */
	public function getSettingLinks(){
               if(!$this->isEntityModule()) {
            return array();
        }
		vimport('~~modules/com_ncrm_workflow/VTWorkflowUtils.php');

		$layoutEditorImagePath = Ncrm_Theme::getImagePath('LayoutEditor.gif');
		$editWorkflowsImagePath = Ncrm_Theme::getImagePath('EditWorkflows.png');
		$settingsLinks = array();

		if(VTWorkflowUtils::checkModuleWorkflow($this->getName())) {
			$settingsLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_EDIT_WORKFLOWS',
                    'linkurl' => 'index.php?parent=Settings&module=Workflows&view=List&sourceModule='.$this->getName(),
					'linkicon' => $editWorkflowsImagePath
			);
		}

        $settingsLinks[] = array(
                    'linktype' => 'LISTVIEWSETTINGS',
                    'linklabel'=> 'LBL_SERVER_CONFIGURATION',
                    'linkurl' => 'index.php?parent=Settings&module=PBXManager&view=Index',
                    'linkicon'=> ''
        );
		return $settingsLinks;
	}
    
    /**
     * Funxtion to identify if the module supports quick search or not
     */
    public function isQuickSearchEnabled() {
        return false;
    }
    
    public function isListViewNameFieldNavigationEnabled() {
        return false;
    }
}
?>
