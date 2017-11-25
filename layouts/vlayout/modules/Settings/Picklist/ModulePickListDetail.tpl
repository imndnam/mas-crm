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
    {if !empty($NO_PICKLIST_FIELDS) }
        <label style="padding-top: 40px;"> <b>
                {vtranslate($SELECTED_MODULE_NAME,$SELECTED_MODULE_NAME)} {vtranslate('NO_PICKLIST_FIELDS',$QUALIFIED_NAME)}. &nbsp; 
            {if !empty($CREATE_PICKLIST_URL)}
                <a href="{$CREATE_PICKLIST_URL}">{vtranslate('LBL_CREATE_NEW',$QUALIFIED_NAME)}</a>
            {/if}
            </b>
        </label>
    {else}
	<div class="row-fluid">
		<label class="fieldLabel span3"><strong>{vtranslate('LBL_SELECT_PICKLIST_IN',$QUALIFIED_MODULE)}&nbsp;{vtranslate($SELECTED_MODULE_NAME,$QUALIFIED_MODULE)}</strong></label>
		<div class="span6 fieldValue">
			<select class="chzn-select" id="modulePickList">
				<optgroup>
					{foreach key=PICKLIST_FIELD item=FIELD_MODEL from=$PICKLIST_FIELDS}
						<option value="{$FIELD_MODEL->getId()}">{vtranslate($FIELD_MODEL->get('label'),$SELECTED_MODULE_NAME)}</option>
					{/foreach}	
				</optgroup>
			</select>
		</div>
	</div><br>
    {/if}
{/strip}	
