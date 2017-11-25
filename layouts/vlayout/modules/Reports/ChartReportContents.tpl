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

<input type='hidden' name='charttype' value="{$CHART_TYPE}" />
<input type='hidden' name='data' value='{$DATA}' />
<input type='hidden' name='clickthrough' value="{$CLICK_THROUGH}" />

<br>
<div style="margin:0px 20px;">
	<div class='border1px' style="padding:30px 100px;">
		<div id='chartcontent' style="min-height:400px;" ></div>
		<br>
		{if $CLICK_THROUGH neq 'true'}
			<div class='row-fluid alert-info' style="padding:20px">
				<span class='span3 offset4'> &nbsp;</span>
				<span class='span alert-info'>
					<i class="icon-info-sign"></i>
					{vtranslate('LBL_CLICK_THROUGH_NOT_AVAILABLE', $MODULE)}
				</span>
			</div>
			<br>
		{/if}
	</div>
</div>
<br>
