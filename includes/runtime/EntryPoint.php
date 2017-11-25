<?php
/*+**********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ************************************************************************************/

vimport ('includes.exceptions.AppException');

vimport ('includes.http.Request');
vimport ('includes.http.Response');
vimport ('includes.http.Session');

vimport ('includes.runtime.Globals');
vimport ('includes.runtime.Controller');
vimport ('includes.runtime.Viewer');
vimport ('includes.runtime.Theme');
vimport ('includes.runtime.BaseModel');
vimport ('includes.runtime.JavaScript');

vimport ('includes.runtime.LanguageHandler');
vimport ('includes.runtime.Cache');

abstract class Ncrm_EntryPoint {

	/**
	 * Login data
	 */
	protected $login = false;

	/**
	 * Get login data.
	 */
	function getLogin() {
		return $this->login;
	}

	/**
	 * Set login data.
	 */
	function setLogin($login) {
		if ($this->login) throw new AppException('Login is already set.');
		$this->login = $login;
	}

	/**
	 * Check if login data is present.
	 */
	function hasLogin() {
		return $this->getLogin()? true: false;
	}

	abstract function process (Ncrm_Request $request);

}