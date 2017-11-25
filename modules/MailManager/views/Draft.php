<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: NCRM Open source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class MailManager_Draft_View extends MailManager_Abstract_View {

	/**
	 * Function to process request, currently not used
	 * @param Ncrm_Request $request
	 */
	public function process(Ncrm_Request $request) {
	}

	/**
	 * Returns a List of search strings on the internal ncrm Drafts
	 * @return Array of ncrm Email Fields
	 */
	public static function getSearchOptions() {
		$options = array('subject'=>'SUBJECT', 'saved_toid'=>'TO','description'=>'BODY','bccmail'=>'BCC','ccmail'=>'CC');
		return $options;
	}

	/**
	 * Function which returns the Draft Model
	 * @return MailManager_Draft_Model
	 */
	public function connectorWithModel() {
		if ($this->mMailboxModel === false) {
			$this->mMailboxModel = MailManager_Draft_Model::getInstance();
		}
		return $this->mMailboxModel;
	}
}
?>