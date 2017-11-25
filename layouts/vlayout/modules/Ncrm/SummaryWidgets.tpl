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
	<input type="hidden" name="page" value="{$PAGING_MODEL->get('page')}" />
	<input type="hidden" name="pageLimit" value="{$PAGING_MODEL->get('limit')}" />
	{if $RELATED_MODULE && $RELATED_RECORDS}
		{assign var=FILENAME value=$RELATED_MODULE|cat:"SummaryWidgetContents.tpl"}
		{include file=$FILENAME|vtemplate_path:$MODULE RELATED_RECORDS=$RELATED_RECORDS}
	{/if}
{/strip}