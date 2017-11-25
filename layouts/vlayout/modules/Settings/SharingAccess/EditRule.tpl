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
    {assign var=RULE_MODEL_EXISTS value=true}
    {assign var=RULE_ID value=$RULE_MODEL->getId()}
    {if empty($RULE_ID)}
        {assign var=RULE_MODEL_EXISTS value=false}
    {/if}
    <div>
        <div class="modal-header contentsBackground">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>{vtranslate('LBL_ADD_CUSTOM_RULE_TO', $QUALIFIED_MODULE)}&nbsp;{vtranslate($MODULE_MODEL->get('name'), $MODULE)}</h3>
        </div>
        <form id="editCustomRule" class="form-horizontal" method="POST">
            <input type="hidden" name="for_module" value="{$MODULE_MODEL->get('name')}" />
            <input type="hidden" name="record" value="{$RULE_ID}" />
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label">{vtranslate($MODULE_MODEL->get('name'), $MODULE)}&nbsp;{vtranslate('LBL_OF', $MODULE)}</label>
                        <div class="controls">
                            <select class="chzn-select" name="source_id">
                                {foreach from=$ALL_RULE_MEMBERS key=GROUP_LABEL item=ALL_GROUP_MEMBERS}
                                    <optgroup label="{vtranslate($GROUP_LABEL, $QUALIFIED_MODULE)}">
                                        {foreach from=$ALL_GROUP_MEMBERS item=MEMBER}
                                            <option value="{$MEMBER->getId()}"
                                        {if $RULE_MODEL_EXISTS} {if $RULE_MODEL->getSourceMember()->getId() == $MEMBER->getId()}selected{/if}{/if}>
                                        {$MEMBER->getName()}
                                    </option>
                                {/foreach}
                            </optgroup>
                        {/foreach}
                    </select>
                </div>	
            </div>
            <div class="control-group">
                <label class="control-label">{vtranslate('LBL_CAN_ACCESSED_BY', $QUALIFIED_MODULE)}</label>
                <div class="controls">
                    <select class="chzn-select" name="target_id">
                        {foreach from=$ALL_RULE_MEMBERS key=GROUP_LABEL item=ALL_GROUP_MEMBERS}
                            <optgroup label="{vtranslate($GROUP_LABEL, $QUALIFIED_MODULE)}">
                                {foreach from=$ALL_GROUP_MEMBERS item=MEMBER}
                                    <option value="{$MEMBER->getId()}"
                                {if $RULE_MODEL_EXISTS}{if $RULE_MODEL->getTargetMember()->getId() == $MEMBER->getId()}selected{/if}{/if}>
                                {$MEMBER->getName()}
                            </option>
                        {/foreach}
                    </optgroup>
                {/foreach}
            </select>
        </div>	
    </div>
    <div class="control-group">
        <label class="control-label">{vtranslate('LBL_WITH_PERMISSIONS', $QUALIFIED_MODULE)}</label>
        <div class="controls">
            <label class="radio">
                <input type="radio" value="0" name="permission" {if $RULE_MODEL_EXISTS} {if $RULE_MODEL->isReadOnly()} checked {/if} {else} checked {/if}/>&nbsp;{vtranslate('LBL_READ', $QUALIFIED_MODULE)}&nbsp;
            </label>
            <label class="radio">
                <input type="radio" value="1" name="permission" {if $RULE_MODEL->isReadWrite()} checked {/if} />&nbsp;{vtranslate('LBL_READ_WRITE', $QUALIFIED_MODULE)}&nbsp;
            </label>
        </div>	
    </div>
</div>
</div>
{include file='ModalFooter.tpl'|@vtemplate_path:'Ncrm'}
</form>
</div>
{/strip}