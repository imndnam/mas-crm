{************************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************}
{strip}
<div style='padding:5px'>
	{foreach item=KEYMETRIC from=$KEYMETRICS}
	<div style='padding:5px'>
		<span class="pull-right">{$KEYMETRIC.count}</span>
		<a href="?module={$KEYMETRIC.module}&view=List&viewname={$KEYMETRIC.id}">{vtranslate($KEYMETRIC.name,$KEYMETRIC.module)}</a>
	</div>	
	{/foreach}
</div>
{/strip}
