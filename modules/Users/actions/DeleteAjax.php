<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
vimport('~~/include/Webservices/Custom/DeleteUser.php');

class Users_DeleteAjax_Action extends Ncrm_Delete_Action {

	public function process(Ncrm_Request $request) {
		$moduleName = $request->getModule();
        $ownerId = $request->get('userid');
        $newOwnerId = $request->get('transfer_user_id');

        if($request->get('mode') == 'permanent') {
            Users_Record_Model::deleteUserPermanently($ownerId, $newOwnerId);
        } else {
            $userId = vtws_getWebserviceEntityId($moduleName, $ownerId);
            $transformUserId = vtws_getWebserviceEntityId($moduleName, $newOwnerId);

            $userModel = Users_Record_Model::getCurrentUserModel();

            vtws_deleteUser($userId, $transformUserId, $userModel);

            if($request->get('permanent') == '1')
                Users_Record_Model::deleteUserPermanently($ownerId, $newOwnerId);
        }
		
		$response = new Ncrm_Response();
		$response->setResult(array('message'=>vtranslate('LBL_USER_DELETED_SUCCESSFULLY', $moduleName)));
		$response->emit();
	}
}
