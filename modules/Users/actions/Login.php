<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

class Users_Login_Action extends Ncrm_Action_Controller {

	function loginRequired() {
		return false;
	}
        
        
        function checkPermission(Ncrm_Request $request) {  
               return true;  
        } 

	function process(Ncrm_Request $request) {
		$username = $request->get('username');
		$password = $request->getRaw('password');

		$user = CRMEntity::getInstance('Users');
		$user->column_fields['user_name'] = $username;

		if ($user->doLogin($password)) {
			session_regenerate_id(true); // to overcome session id reuse.

			$userid = $user->retrieve_user_id($username);
			Ncrm_Session::set('AUTHUSERID', $userid);

			// For Backward compatability
			// TODO Remove when switch-to-old look is not needed
			$_SESSION['authenticated_user_id'] = $userid;
			$_SESSION['app_unique_key'] = vglobal('application_unique_key');
			$_SESSION['authenticated_user_language'] = vglobal('default_language');
            
            		//Enabled session variable for KCFINDER 
            		$_SESSION['KCFINDER'] = array(); 
            		$_SESSION['KCFINDER']['disabled'] = false; 
            		$_SESSION['KCFINDER']['uploadURL'] = "test/upload"; 
            		$_SESSION['KCFINDER']['uploadDir'] = "../test/upload";
			$deniedExts = implode(" ", vglobal('upload_badext'));
			$_SESSION['KCFINDER']['deniedExts'] = $deniedExts;
			// End

			//Track the login History
			$moduleModel = Users_Module_Model::getInstance('Users');
			$moduleModel->saveLoginHistory($user->column_fields['user_name']);
			//End
            
              if(isset($_SESSION['return_params'])){ 
					$return_params = $_SESSION['return_params'];
				}

			header ('Location: index.php?module=Users&parent=Settings&view=SystemSetup');
			exit();
		} else {
			header ('Location: index.php?module=Users&parent=Settings&view=Login&error=1');
			exit;
		}
	}
	
		}
