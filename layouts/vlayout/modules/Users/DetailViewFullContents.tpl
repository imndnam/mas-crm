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
	{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
	{include file='DetailViewBlockView.tpl'|@vtemplate_path:$MODULE_NAME RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE_NAME=$MODULE_NAME}
	{* Tag Cloud block starts *}
	<table class="table equalSplit detailview-table">
		<thead>
			<tr>
				<th class="blockHeader" colspan="4">
					{vtranslate('LBL_TAG_CLOUD_DISPLAY', $MODULE_NAME)}
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}" id="{$MODULE}_detailView_fieldLabel_tagCloud">
					<label class="muted marginRight10px">
						{vtranslate('LBL_TAG_CLOUD', $MODULE_NAME)}
					</label>
				</td>
				<td class="fieldValue {$WIDTHTYPE}" id="{$MODULE}_detailView_fieldValue_tagCloud">
					{assign var=TAG_CLOUD value=$RECORD->getTagCloudStatus()}
					{if $TAG_CLOUD}
						<img src={"prvPrfSelectedTick.gif"|vimage_path} alt="{vtranslate('LBL_SHOWN', $MODULE_NAME)}" title="{vtranslate('LBL_SHOWN', $MODULE_NAME)}" height="12" width="12">&nbsp;&nbsp;{vtranslate('LBL_SHOWN', $MODULE_NAME)}
					{else}
						<img src={"no.gif"|vimage_path} alt="{vtranslate('LBL_HIDDEN', $MODULE_NAME)}" title="{vtranslate('LBL_HIDDEN', $MODULE_NAME)}" height="12" width="12">&nbsp;&nbsp;{vtranslate('LBL_HIDDEN', $MODULE_NAME)}
					{/if}
				</td><td class="{$WIDTHTYPE}"></td><td class="{$WIDTHTYPE}"></td>
			</tr>
		</tbody>
	</table>
	<br>
	{* Tag Clous block ends *}
{/strip}