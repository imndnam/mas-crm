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
	{* javascript files *}
	{include file='JSResources.tpl'|@vtemplate_path}
	</body>
</html>
{/strip}
<script type=text/javascript>
	jQuery(document).ready(function() {
		jQuery.triggerParentEvent('Ncrm.OnPopupWindowLoad.Event');
	});
</script>