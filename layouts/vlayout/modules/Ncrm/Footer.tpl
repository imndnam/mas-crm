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
		<input id='activityReminder' class='hide noprint' type="hidden" value="{$ACTIVITY_REMINDER}"/>

		{* Feedback side-panel button *}
		{if $HEADER_LINKS && $MAIN_PRODUCT_SUPPORT && !$MAIN_PRODUCT_WHITELABEL}
		{assign var="FIRSTHEADERLINK" value=$HEADER_LINKS.0}
		{assign var="FIRSTHEADERLINKCHILDRENS" value=$FIRSTHEADERLINK->get('childlinks')}
		{assign var="FEEDBACKLINKMODEL" value=$FIRSTHEADERLINKCHILDRENS.2}
		<div id="userfeedback" class="feedback noprint">
			<a href="https://discussions.ncrm.com" target="_blank" xonclick="{$FEEDBACKLINKMODEL->get('linkurl')}" class="handle">{vtranslate("LBL_FEEDBACK", "Ncrm")}</a>
		</div>
		{/if}

		{if !$MAIN_PRODUCT_WHITELABEL && isset($CURRENT_USER_MODEL)}
		{*<footer class="noprint">*}
                    {*<div class="vtFooter">*}
			{*<p>*}
				{*{vtranslate('POWEREDBY')} {$NCRM_VERSION} &nbsp;*}
			{*</p>*}
                     {*</div>*}
		{*</footer>*}
		{/if}
		
		{* javascript files *}
		{include file='JSResources.tpl'|@vtemplate_path}
		</div>
		
	</body>
</html>
{/strip}
