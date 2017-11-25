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
{if !empty($MODULE_SUMMARY)}
	<div class="row-fluid">
		<div class="span7">
			<div class="summaryView row-fluid">
			{$MODULE_SUMMARY}
			</div>
{/if}
			{foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET'] name=count}
				{if $smarty.foreach.count.index % 2 == 0}
					<div class="summaryWidgetContainer">
						<div class="widgetContainer_{$smarty.foreach.count.index}" data-url="{$DETAIL_VIEW_WIDGET->getUrl()}" data-name="{$DETAIL_VIEW_WIDGET->getLabel()}">
							<div class="widget_header row-fluid">
								<span class="span8 margin0px"><h4>{vtranslate($DETAIL_VIEW_WIDGET->getLabel(),$MODULE_NAME)}</h4></span>
							</div>
							<div class="widget_contents">
							</div>
						</div>
					</div>
				{/if}
			{/foreach}
		</div>
		<div class="span5" style="overflow: hidden">
			<div id="relatedActivities">
				{$RELATED_ACTIVITIES}
			</div>
			{foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET'] name=count}
				{if $smarty.foreach.count.index % 2 != 0}
					<div class="summaryWidgetContainer">
						<div class="widgetContainer_{$smarty.foreach.count.index}" data-url="{$DETAIL_VIEW_WIDGET->getUrl()}" data-name="{$DETAIL_VIEW_WIDGET->getLabel()}">
							<div class="widget_header row-fluid">
								<span class="span8 margin0px"><h4>{vtranslate($DETAIL_VIEW_WIDGET->getLabel(),$MODULE_NAME)}</h4></span>
							</div>
							<div class="widget_contents">
							</div>
						</div>
					</div>
				{/if}
			{/foreach}
		</div>
	</div>
{/strip}