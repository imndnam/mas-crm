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
    {assign var="FIELD_INFO" value=Zend_Json::encode($FIELD_MODEL->getFieldInfo())}
    {assign var=SEARCH_VALUES value=$SEARCH_INFO['searchValue']}
    <div class="row-fluid">
    <select class="select2 listSearchContributor" name="{$FIELD_MODEL->get('name')}" style="width:90px;" data-fieldinfo='{$FIELD_INFO|escape}'>
        <option value="">{vtranslate('LBL_SELECT_OPTION','Ncrm')}</option>
        <option value="1" {if $SEARCH_VALUES eq 1} selected{/if}>{vtranslate('LBL_YES',$MODULE)}</option>
        <option value="0" {if $SEARCH_VALUES eq '0'} selected{/if}>{vtranslate('LBL_NO',$MODULE)}</option>
    </select>
    </div>
{/strip}