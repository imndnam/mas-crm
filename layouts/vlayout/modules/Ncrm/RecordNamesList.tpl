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
<div class="recordNamesList">
	<div class="row-fluid">
		<div class="">
			<ul class="nav nav-list">
				{foreach item=recordsModel from=$RECORDS}
				<li>
					<a data-id={$recordsModel->getId()} href="{$recordsModel->getDetailViewUrl()}" title="{decode_html($recordsModel->getName())}">{decode_html($recordsModel->getName())}</a>
				</li>
				{foreachelse}
					<li style="text-align:center">{vtranslate('LBL_NO_RECORDS', $MODULE)}
					</li>
				{/foreach}

			</ul>
		</div>
	</div>
</div>
{/strip}
