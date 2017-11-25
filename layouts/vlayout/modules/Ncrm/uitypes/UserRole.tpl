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
{assign var=FIELD_NAME value=$FIELD_MODEL->get('name')}
{assign var="FIELD_INFO" value=Ncrm_Util_Helper::toSafeHTML(Zend_Json::encode($FIELD_MODEL->getFieldInfo()))}
{assign var=PICKLIST_VALUES value=$FIELD_MODEL->getAllRoles()}
{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
<select class="chzn-select" name="{$FIELD_MODEL->getFieldName()}" data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true} required,{/if}funcCall[Ncrm_Base_Validator_Js.invokeValidation]]" data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator='{Zend_Json::encode($SPECIAL_VALIDATOR)}'{/if} >
    {foreach item=PICKLIST_VALUE key=PICKLIST_NAME from=$PICKLIST_VALUES}
	<option value="{$PICKLIST_VALUE}" {if $FIELD_MODEL->get('fieldvalue') eq $PICKLIST_VALUE} selected {/if}>{vtranslate($PICKLIST_NAME, $MODULE)}</option>
{/foreach}
</select>
{/strip}