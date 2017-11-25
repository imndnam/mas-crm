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

<input type="hidden" name="{$FIELD_MODEL->getFieldName()}" value=0 />
<input id="{$MODULE}_editView_fieldName_{$FIELD_NAME}" type="checkbox" name="{$FIELD_MODEL->getFieldName()}" data-validation-engine="validate[funcCall[Ncrm_Base_Validator_Js.invokeValidation]]"
{if $FIELD_MODEL->get('fieldvalue') eq true} checked
{/if} data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator={Zend_Json::encode($SPECIAL_VALIDATOR)}{/if} />
{/strip}