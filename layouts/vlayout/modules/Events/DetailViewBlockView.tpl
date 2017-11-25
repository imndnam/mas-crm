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
    {include file='DetailViewBlockView.tpl'|@vtemplate_path:'Ncrm' RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE_NAME=$MODULE_NAME}

    {assign var="IS_HIDDEN" value=false}
    <table class="table equalSplit detailview-table">
		<thead>
		<tr>
				<th class="blockHeader" colspan="4">
						<img class="cursorPointer alignMiddle blockToggle {if !($IS_HIDDEN)} hide {/if} "  src="{vimage_path('arrowRight.png')}" data-mode="hide" data-id='INVITE_USER_BLOCK_ID'>
						<img class="cursorPointer alignMiddle blockToggle {if ($IS_HIDDEN)} hide {/if}"  src="{vimage_path('arrowDown.png')}" data-mode="show" data-id='INVITE_USER_BLOCK_ID'>
						&nbsp;&nbsp;{vtranslate('LBL_INVITE_USER_BLOCK',{$MODULE_NAME})}
				</th>
		</tr>
		</thead>
        <tr>
            <td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('LBL_INVITE_USERS',$MODULE_NAME)}</label></td>
            <td class="fieldValue {$WIDTHTYPE}">
                 {foreach key=USER_ID item=USER_NAME from=$ACCESSIBLE_USERS}
					{if in_array($USER_ID,$INVITIES_SELECTED)}
                        {$USER_NAME}
                        <br>
                    {/if}
                {/foreach}
            </td>
        </tr>
   </table>
{/strip}