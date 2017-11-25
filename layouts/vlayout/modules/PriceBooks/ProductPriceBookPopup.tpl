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
    <div id="popupPageContainer" class="contentsDiv">
        <div class="paddingLeftRight10px">{include file='PopupSearch.tpl'|@vtemplate_path:$MODULE_NAME}
            <form id="popupPage" action="javascript:void(0)">
                <div id="popupContents">{include file='ProductPriceBookPopupContents.tpl'|@vtemplate_path:$PARENT_MODULE}</div>
            </form>
        </div>
        <input type="hidden" class="triggerEventName" value="{getPurifiedSmartyParameters('triggerEventName')}"/>
    </div>
</div>
{/strip}