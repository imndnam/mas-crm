<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_DetailView_Model extends Ncrm_DetailView_Model {

	/**
	 * Function to get the detail view related links
	 * @return <array> - list of links parameters
	 */
	public function getDetailViewRelatedLinks() {
		$recordModel = $this->getRecord();
        $moduleName = $recordModel->getType();
		$relatedLinks = array();
		//link which shows the summary information(generally detail of record)
		$relatedLinks[] = array(
				'linktype' => 'DETAILVIEWTAB',
				'linklabel' => vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName),
				'linkurl' => $recordModel->getDetailViewUrl().'&mode=showDetailViewByMode&requestMode=full',
				'linkicon' => ''
		);

		$parentModuleModel = $this->getModule();
		if($parentModuleModel->isTrackingEnabled()) {
			$relatedLinks[] = array(
					'linktype' => 'DETAILVIEWTAB',
					'linklabel' => vtranslate('LBL_UPDATES'),
					'linkurl' => $recordModel->getDetailViewUrl().'&mode=showRecentActivities&page=1',
					'linkicon' => ''
			);
		}
		return $relatedLinks;
	}
}
