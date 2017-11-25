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
<script type="text/javascript">
	Ncrm_Pie_Widget_Js('Ncrm_Totalrevenuepersalesperson_Widget_Js',{},{});
</script>
<div class="dashboardWidgetHeader">
	{include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME SETTING_EXIST=true}
	<div class="row-fluid filterContainer hide" style="position:absolute;z-index:100001">
		<div class="row-fluid">
			<span class="span5">
				<span class="pull-right">
					{vtranslate('Created Time', $MODULE_NAME)} &nbsp; {vtranslate('LBL_BETWEEN', $MODULE_NAME)}
				</span>
			</span>
			<span class="span4">
				<input type="text" name="createdtime" class="dateRange widgetFilter" />
			</span>
		</div>
	</div>			
</div>
<div class="dashboardWidgetContent">
	{include file="dashboards/DashBoardWidgetContents.tpl"|@vtemplate_path:$MODULE_NAME}
</div>