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
<div class="quickLinksDiv">
    {assign var=SIDEBARLINK value=$QUICK_LINKS['SIDEBARLINK'][0]}
    <div style="margin-bottom: 5px" class="btn-group row-fluid">
        <button id="rssAddButton" class="btn addButton span12 rssAddButton" data-href="{$SIDEBARLINK->getUrl()}">
            <img src="layouts/vlayout/skins/images/rss_add.png" />
            <strong>&nbsp;&nbsp; {vtranslate($SIDEBARLINK->getLabel(), $MODULE)}</strong>
        </button>
    </div>
    <div class="rssAddFormContainer hide">
    </div>
</div>
{/strip}