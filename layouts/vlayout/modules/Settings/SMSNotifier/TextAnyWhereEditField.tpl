{*<!--
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  NCRM Open Source
* The Initial Developer of the Original Code is ncrm.
* Portions created by ncrm are Copyright (C) ncrm.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
	{include file="ProviderEditFields.tpl"|@vtemplate_path:$QUALIFIED_MODULE_NAME}
	<div class="alert-info alert">
		<div>
			In the Originator field, enter one of the following:<br /><br />
			1. The 11 characters to be sent with each SMS<br />
			2. The mobile number to be sent with each SMS<br />
			3. The email address to which any SMS replies will be sent<br />
		</div>
		<br>
		<div class="row-fluid">
			<div class="span3">
				<a href="http://www.textanywhere.net/static/Products/NCrm_Capabilities.aspx" target="_blank">Help</a>
			</div>
			<div class="span3">
				<a href="https://www.textapp.net/web/textanywhere/" target="_blank">Account Login</a>
			</div>
			<div class="span3">
				<a href="https://www.textapp.net/web/textanywhere/Register/Register.aspx" target="_blank">Create Account</a>
			</div>
		</div>
	</div>	
{/strip}		