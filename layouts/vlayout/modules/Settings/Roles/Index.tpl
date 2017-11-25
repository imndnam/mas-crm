{*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************}
{strip}
<div class="container-fluid">
	<div class="widget_header row-fluid">
		<div class="span8">
			<h3>{vtranslate($MODULE, $QUALIFIED_MODULE)}</h3>
	</div>	
	</div>
	<hr>
	<div class="clearfix treeView">
		<ul>
			<li data-role="{$ROOT_ROLE->getParentRoleString()}" data-roleid="{$ROOT_ROLE->getId()}">
				<div class="toolbar-handle">
					<a href="javascript:;" class="btn btn-inverse draggable droppable">{$ROOT_ROLE->getName()}</a>
					<div class="toolbar" title="{vtranslate('LBL_ADD_RECORD', $QUALIFIED_MODULE)}">
						&nbsp;<a href="{$ROOT_ROLE->getCreateChildUrl()}" data-url="{$ROOT_ROLE->getCreateChildUrl()}" data-action="modal"><span class="icon-plus-sign"></span></a>
					</div>
				</div>
				{assign var="ROLE" value=$ROOT_ROLE}
				{include file=vtemplate_path("RoleTree.tpl", "Settings:Roles")}
			</li>
		</ul>
	</div>
</div>
{/strip}