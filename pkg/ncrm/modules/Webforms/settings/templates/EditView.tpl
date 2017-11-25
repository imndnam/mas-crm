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
{include file="EditViewBlocks.tpl"|@vtemplate_path:Ncrm}
<div class="targetFieldsTableContainer">
	{include file="FieldsEditView.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
</div>
<br>	
{include file="EditViewActions.tpl"|@vtemplate_path:Ncrm}
<div class="row-fluid" style="margin-bottom:150px;"></div>
{/strip}