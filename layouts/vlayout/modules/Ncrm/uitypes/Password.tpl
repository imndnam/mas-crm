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
	{assign var="FIELD_INFO" value=Ncrm_Util_Helper::toSafeHTML(Zend_Json::encode($FIELD_MODEL->getFieldInfo()))}
	{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
	{assign var="FIELD_NAME" value=$FIELD_MODEL->get('name')}
	
	<input id="{$MODULE}_editView_fieldName_{$FIELD_NAME}" type="password" 
		   class="input-large {if $FIELD_MODEL->isNameField()}nameField{/if}" 
		   data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true}required,{/if}funcCall[Ncrm_Base_Validator_Js.invokeValidation]]"
		   name="{$FIELD_MODEL->getFieldName()}" 
		   value="{$FIELD_MODEL->get('fieldvalue')}"
		  data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator={Zend_Json::encode($SPECIAL_VALIDATOR)}{/if} 
	/>
{/strip}