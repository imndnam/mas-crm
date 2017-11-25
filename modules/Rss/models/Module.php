<?php
/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Rss_Module_Model extends Ncrm_Module_Model {

	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Ncrm_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
		$links = Ncrm_Link_Model::getAllByType($this->getId(), $linkTypes, $linkParams);

		$quickLinks = array(
			array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_ADD_FEED_SOURCE',
				'linkurl' => $this->getDefaultUrl(),
				'linkicon' => '',
			)
		);
		foreach($quickLinks as $quickLink) {
			$links['SIDEBARLINK'][] = Ncrm_Link_Model::getInstanceFromValues($quickLink);
		}
        $quickWidgets = array(
			array(
				'linktype' => 'SIDEBARWIDGET',
				'linklabel' => 'LBL_RSS_FEED_SOURCES',
				'linkurl' => 'module='.$this->get('name').'&view=ViewTypes&mode=getRssWidget',
				'linkicon' => ''
			),
		);
		foreach($quickWidgets as $quickWidget) {
			$links['SIDEBARWIDGET'][] = Ncrm_Link_Model::getInstanceFromValues($quickWidget);
		}
        
		return $links;
	}
    
    /**
     * Function to get rss sources list
     */
    public function getRssSources() { 
        $db = PearDatabase::getInstance();
        
        $sql = 'Select *from ncrm_rss';
        $result = $db->pquery($sql, array());
        $noOfRows = $db->num_rows($result);

		$records = array();
		for($i=0; $i<$noOfRows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$row['id'] = $row['rssid'];
			$records[$row['id']] = $this->getRecordFromArray($row);
		}
        return $records;
    }
}
