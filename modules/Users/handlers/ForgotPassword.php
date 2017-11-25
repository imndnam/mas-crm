<?php

/* +***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 * *********************************************************************************** */

class Users_ForgotPassword_Handler {

    public function changePassword($data) {
        global $site_URL;
        $site_URL= rtrim($site_URL,'/'); 
        $request = new Ncrm_Request($data);
        $userName = $request->get('username');
        $viewer = Ncrm_Viewer::getInstance();
        $companyModel = Ncrm_CompanyDetails_Model::getInstanceById();
        $companyName = $companyModel->get('organizationname');
        $organisationDetails = $companyModel->getLogo();
        $logoTitle = $organisationDetails->get('title');
        $logoName = $organisationDetails->get('imagename');
        $moduleName = 'Users';
        $viewer->assign('LOGOURL', $site_URL . '/test/logo/' . $logoName);
        $viewer->assign('TITLE', $logoTitle);
        $viewer->assign('COMPANYNAME', $companyName);
        $viewer->assign('USERNAME', $userName);
        $changePasswordTrackUrl = $site_URL . "/modules/Users/actions/ForgotPassword.php";
        $viewer->assign('TRACKURL', $changePasswordTrackUrl);
        $expiryTime = (int) $request->get('time') + (24 * 60 * 60);
        $currentTime = time();
        if ($expiryTime > $currentTime) {
            $secretToken = uniqid();
            $secretHash = md5($userName . $secretToken);
            $options = array(
                'handler_path' => 'modules/Users/handlers/ForgotPassword.php',
                'handler_class' => 'Users_ForgotPassword_Handler',
                'handler_function' => 'changePassword',
                'onetime' => 1,
                'handler_data' => array(
                    'username' => $userName,
                    'secret_token' => $secretToken,
                    'secret_hash' => $secretHash
                )
            );
            $trackURL = Ncrm_ShortURL_Helper::generateURL($options);
            $shortURLID = explode('id=', $trackURL);
            $viewer->assign('SHORTURL_ID', $shortURLID[1]);
            $viewer->assign('SECRET_HASH', $secretHash);
        } else {
            $viewer->assign('LINK_EXPIRED', true);
        }

        $viewer->assign('TRACKURL', $changePasswordTrackUrl);
        $viewer->assign('MODULE', $moduleName);
        $viewer->view('ForgotPassword.tpl', $moduleName);
    }

}
