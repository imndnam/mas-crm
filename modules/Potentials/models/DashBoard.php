<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Potentials_DashBoard_Model extends Ncrm_DashBoard_Model {

	/**
	 * Function to get the default widgets
	 * @return <array> - array of Widget models
	 */
	public function getDefaultWidgets() {
		$moduleModel = $this->getModule();
		$parentWidgets = parent::getDefaultWidgets();

		$widgets[] = array(
			'contentType' => 'json',
			'title' => 'Opportunity By Sales Stage',
			'mode' => 'open',
			'url' => 'module='. $moduleModel->getName().'&view=ShowWidget&mode=getPotentialsCountBySalesStage'
		);

		foreach($widgets as $widget) {
			$widgetList[] = Ncrm_Widget_Model::getInstanceFromValues($widget);
		}

		return $widgetList;
	}
}