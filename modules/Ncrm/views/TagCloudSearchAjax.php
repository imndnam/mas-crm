<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_TagCloudSearchAjax_View extends Ncrm_IndexAjax_View {

	function process(Ncrm_Request $request) {
		
		$tagId = $request->get('tag_id');
		$taggedRecords = Ncrm_Tag_Model::getTaggedRecords($tagId);
		
		$viewer = $this->getViewer($request);
		
		$viewer->assign('TAGGED_RECORDS',$taggedRecords);
		$viewer->assign('TAG_NAME',$request->get('tag_name'));
		
		echo $viewer->view('TagCloudResults.tpl', $module, true);
	}
	
	
	
}