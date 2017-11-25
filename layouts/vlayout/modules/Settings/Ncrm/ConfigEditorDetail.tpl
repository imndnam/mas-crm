{*<!--
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
{strip}
<div class="container-fluid" id="ConfigEditorDetails">
	{assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
	<div class="widget_header row-fluid">
		<div class="span8"><h3>{vtranslate('LBL_CONFIG_EDITOR', $QUALIFIED_MODULE)}</h3></div>
		<div class="span4">
			<div class="pull-right">
				<button class="btn editButton" data-url='{$MODEL->getEditViewUrl()}' type="button" title="{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}"><strong>{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}</strong></button>
			</div>
		</div>
	</div>
	<hr>
	<div class="contents">
		<table class="table table-bordered table-condensed themeTableColor">
			<thead>
				<tr class="blockHeader">
					<th colspan="2" class="{$WIDTHTYPE}">
						<span class="alignMiddle">{vtranslate('LBL_CONFIG_FILE', $QUALIFIED_MODULE)}</span>
					</th>
				</tr>
			</thead>
			<tbody>
				{assign var=FIELD_DATA value=$MODEL->getViewableData()}
				{foreach key=FIELD_NAME item=FIELD_DETAILS from=$MODEL->getEditableFields()}
					<tr><td width="30%" class="{$WIDTHTYPE}"><label class="muted marginRight10px pull-right">{vtranslate($FIELD_DETAILS['label'], $QUALIFIED_MODULE)}</label></td>
						<td style="border-left: none;" class="{$WIDTHTYPE}">
							<span>{if $FIELD_NAME == 'default_module'}{vtranslate($FIELD_DATA[$FIELD_NAME], $FIELD_DATA[$FIELD_NAME])}
								{else if $FIELD_DETAILS['fieldType'] == 'checkbox'}{vtranslate($FIELD_DATA[$FIELD_NAME], $QUALIFIED_MODULE)}
								{else}{$FIELD_DATA[$FIELD_NAME]}{/if}
								{if $FIELD_NAME == 'upload_maxsize'}&nbsp;{vtranslate('LBL_MB', $QUALIFIED_MODULE)}{/if}</span>
						</td></tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>
{/strip}