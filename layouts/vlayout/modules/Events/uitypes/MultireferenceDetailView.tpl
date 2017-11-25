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
    {foreach item=CONTACT_INFO from=$RELATED_CONTACTS}
        <a href='{$CONTACT_INFO['_model']->getDetailViewUrl()}' title='{vtranslate("Contacts", "Contacts")}'> {Ncrm_Util_Helper::getRecordName($CONTACT_INFO['id'])}</a>
        <br>
    {/foreach}
{/strip}