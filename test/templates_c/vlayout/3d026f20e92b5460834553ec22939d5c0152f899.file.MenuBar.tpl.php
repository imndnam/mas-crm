<?php /* Smarty version Smarty-3.1.7, created on 2017-10-26 17:10:37
         compiled from "E:\MXampp\sites\vtiger.dev\ncrm\includes\runtime/../../layouts/vlayout\modules\Ncrm\MenuBar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2348587b777e145ac6-37657751%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d026f20e92b5460834553ec22939d5c0152f899' => 
    array (
      0 => 'E:\\MXampp\\sites\\vtiger.dev\\ncrm\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\MenuBar.tpl',
      1 => 1509037836,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2348587b777e145ac6-37657751',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_587b777e8b62b',
  'variables' => 
  array (
    'MENU_STRUCTURE' => 0,
    'moreMenus' => 0,
    'MODULE' => 0,
    'HOME_MODULE_MODEL' => 0,
    'topMenus' => 0,
    'moduleModel' => 0,
    'moduleName' => 0,
    'MENU_TOPITEMS_LIMIT' => 0,
    'MENU_SELECTED_MODULENAME' => 0,
    'topmenuClassName' => 0,
    'translatedModuleLabel' => 0,
    'moduleList' => 0,
    'NUMBER_OF_PARENT_TABS' => 0,
    'SPAN_CLASS' => 0,
    'parent' => 0,
    'USER_MODEL' => 0,
    'MENUS' => 0,
    'singularLabel' => 0,
    'quickCreateModule' => 0,
    'profileImages' => 0,
    'profileImage' => 0,
    'HEADER_LINKS' => 0,
    'obj' => 0,
    'childLinks' => 0,
    'href' => 0,
    'label' => 0,
    'onclick' => 0,
    'ANNOUNCEMENT' => 0,
    'announcement' => 0,
    'PARENT_MODULE' => 0,
    'VIEW' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b777e8b62b')) {function content_587b777e8b62b($_smarty_tpl) {?>
<?php $_smarty_tpl->tpl_vars["topMenus"] = new Smarty_variable($_smarty_tpl->tpl_vars['MENU_STRUCTURE']->value->getTop(), null, 0);?><?php $_smarty_tpl->tpl_vars["moreMenus"] = new Smarty_variable($_smarty_tpl->tpl_vars['MENU_STRUCTURE']->value->getMore(), null, 0);?><?php $_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS'] = new Smarty_variable(count(array_keys($_smarty_tpl->tpl_vars['moreMenus']->value)), null, 0);?><div class="navbar" id="topMenus" style="overflow: hidden; height: 50px;"><div class="navbar-inner" id="nav-inner"><div class="menuBar row-fluid"><div class="span9"><ul class="nav modulesList" id="largeNav"><li class="tabs"><a class="alignMiddle <?php if ($_smarty_tpl->tpl_vars['MODULE']->value=='Home'){?> selected <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['HOME_MODULE_MODEL']->value->getDefaultUrl();?>
"><span class="fa fa-bar-chart"></span>&nbsp;<span>FBMarketing</span></a></li><?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topMenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['topmenu']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['topmenu']['index']++;
?><?php $_smarty_tpl->tpl_vars['translatedModuleLabel'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['moduleModel']->value->get('label'),$_smarty_tpl->tpl_vars['moduleName']->value), null, 0);?><?php $_smarty_tpl->tpl_vars["topmenuClassName"] = new Smarty_variable("tabs", null, 0);?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['topmenu']['index']>$_smarty_tpl->tpl_vars['MENU_TOPITEMS_LIMIT']->value&&$_smarty_tpl->tpl_vars['MENU_SELECTED_MODULENAME']->value!=$_smarty_tpl->tpl_vars['moduleName']->value){?><?php $_smarty_tpl->tpl_vars["topmenuClassName"] = new Smarty_variable("tabs opttabs", null, 0);?><?php }?><li class="<?php echo $_smarty_tpl->tpl_vars['topmenuClassName']->value;?>
"><a id="menubar_item_<?php echo $_smarty_tpl->tpl_vars['moduleName']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getDefaultUrl();?>
" <?php if ($_smarty_tpl->tpl_vars['MODULE']->value==$_smarty_tpl->tpl_vars['moduleName']->value){?> class="selected" <?php }?>><strong><?php echo $_smarty_tpl->tpl_vars['translatedModuleLabel']->value;?>
</strong></a></li><?php } ?></ul><ul class="nav" id="shortNav"><li class="tabs"><a class="alignMiddle <?php if ($_smarty_tpl->tpl_vars['MODULE']->value=='Home'){?> selected <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['HOME_MODULE_MODEL']->value->getDefaultUrl();?>
"><img src="<?php echo vimage_path('home.png');?>
" alt="<?php echo vtranslate('LBL_HOME',$_smarty_tpl->tpl_vars['moduleName']->value);?>
" title="<?php echo vtranslate('LBL_HOME',$_smarty_tpl->tpl_vars['moduleName']->value);?>
" /></a></li><li class="dropdown tabs" id="collapsedMenu"><a class="dropdown-toggle" data-toggle="dropdown" href="#collapsedMenu"><?php echo vtranslate('LBL_ALL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<b class="caret"></b></a><div class="dropdown-menu"><div class="shortDropdown"><?php  $_smarty_tpl->tpl_vars['moduleList'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleList']->_loop = false;
 $_smarty_tpl->tpl_vars['parent'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['moreMenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['moduleList']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['moduleList']->iteration=0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['more']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['moduleList']->key => $_smarty_tpl->tpl_vars['moduleList']->value){
$_smarty_tpl->tpl_vars['moduleList']->_loop = true;
 $_smarty_tpl->tpl_vars['parent']->value = $_smarty_tpl->tpl_vars['moduleList']->key;
 $_smarty_tpl->tpl_vars['moduleList']->iteration++;
 $_smarty_tpl->tpl_vars['moduleList']->last = $_smarty_tpl->tpl_vars['moduleList']->iteration === $_smarty_tpl->tpl_vars['moduleList']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['more']['index']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['more']['last'] = $_smarty_tpl->tpl_vars['moduleList']->last;
?><?php if ($_smarty_tpl->tpl_vars['moduleList']->value){?><strong><?php echo vtranslate("LBL_".($_smarty_tpl->tpl_vars['parent']->value),$_smarty_tpl->tpl_vars['moduleName']->value);?>
</strong><hr><?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['moduleList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
?><?php $_smarty_tpl->tpl_vars['translatedModuleLabel'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['moduleModel']->value->get('label'),$_smarty_tpl->tpl_vars['moduleName']->value), null, 0);?><label class="moduleNames"><a id="menubar_item_<?php echo $_smarty_tpl->tpl_vars['moduleName']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getDefaultUrl();?>
"><?php echo $_smarty_tpl->tpl_vars['translatedModuleLabel']->value;?>
</a></label><?php } ?><?php }?><?php } ?></div></div></li></ul><ul class="nav modulesList" id="mediumNav"><li class="tabs"><a class="alignMiddle <?php if ($_smarty_tpl->tpl_vars['MODULE']->value=='Home'){?> selected <?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['HOME_MODULE_MODEL']->value->getDefaultUrl();?>
"><img src="<?php echo vimage_path('home.png');?>
" alt="<?php echo vtranslate('LBL_HOME',$_smarty_tpl->tpl_vars['moduleName']->value);?>
" title="<?php echo vtranslate('LBL_HOME',$_smarty_tpl->tpl_vars['moduleName']->value);?>
" /></a></li><?php $_smarty_tpl->tpl_vars['COUNTER'] = new Smarty_variable(0, null, 0);?><?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['topMenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['topmenu']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['topmenu']['index']++;
?><?php $_smarty_tpl->tpl_vars['translatedModuleLabel'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['moduleModel']->value->get('label'),$_smarty_tpl->tpl_vars['moduleName']->value), null, 0);?><?php $_smarty_tpl->tpl_vars["topmenuClassName"] = new Smarty_variable("tabs", null, 0);?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['topmenu']['index']>2&&$_smarty_tpl->tpl_vars['MENU_SELECTED_MODULENAME']->value!=$_smarty_tpl->tpl_vars['moduleName']->value){?><?php $_smarty_tpl->tpl_vars["topmenuClassName"] = new Smarty_variable("tabs opttabs", null, 0);?><?php }?><li class="<?php echo $_smarty_tpl->tpl_vars['topmenuClassName']->value;?>
"><a id="menubar_item_<?php echo $_smarty_tpl->tpl_vars['moduleName']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getDefaultUrl();?>
" <?php if ($_smarty_tpl->tpl_vars['MODULE']->value==$_smarty_tpl->tpl_vars['moduleName']->value){?> class="selected" <?php }?>><strong><?php echo $_smarty_tpl->tpl_vars['translatedModuleLabel']->value;?>
</strong></a></li><?php } ?></ul><ul class="nav" id="commonMoreMenu"><li class="dropdown" id="moreMenu"><a class="dropdown-toggle" data-toggle="dropdown" href="#moreMenu"><div class="more-dots clearfix"><span class="fa fa-chevron-down"></span></div></a><div class="dropdown-menu moreMenus" <?php if (($_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS']->value<=2)&&($_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS']->value!=0)){?>style="width: 30em;"<?php }elseif($_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS']->value==0){?>style="width: 10em;"<?php }?>><?php  $_smarty_tpl->tpl_vars['moduleList'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleList']->_loop = false;
 $_smarty_tpl->tpl_vars['parent'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['moreMenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['moduleList']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['moduleList']->iteration=0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['more']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['moduleList']->key => $_smarty_tpl->tpl_vars['moduleList']->value){
$_smarty_tpl->tpl_vars['moduleList']->_loop = true;
 $_smarty_tpl->tpl_vars['parent']->value = $_smarty_tpl->tpl_vars['moduleList']->key;
 $_smarty_tpl->tpl_vars['moduleList']->iteration++;
 $_smarty_tpl->tpl_vars['moduleList']->last = $_smarty_tpl->tpl_vars['moduleList']->iteration === $_smarty_tpl->tpl_vars['moduleList']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['more']['index']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['more']['last'] = $_smarty_tpl->tpl_vars['moduleList']->last;
?><?php if ($_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS']->value>=4){?><?php $_smarty_tpl->tpl_vars['SPAN_CLASS'] = new Smarty_variable('span3', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS']->value==3){?><?php $_smarty_tpl->tpl_vars['SPAN_CLASS'] = new Smarty_variable('span4', null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['NUMBER_OF_PARENT_TABS']->value<=2){?><?php $_smarty_tpl->tpl_vars['SPAN_CLASS'] = new Smarty_variable('span6', null, 0);?><?php }?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['more']['index']%4==0){?><div class="row-fluid"><?php }?><span class="<?php echo $_smarty_tpl->tpl_vars['SPAN_CLASS']->value;?>
"><strong class="group-name"><span class="fa fa-<?php if ($_smarty_tpl->tpl_vars['parent']->value=='MARKETING_AND_SALES'){?>shopping-bag<?php }elseif($_smarty_tpl->tpl_vars['parent']->value=='SUPPORT'){?>life-ring<?php }elseif($_smarty_tpl->tpl_vars['parent']->value=='INVENTORY'){?>cubes<?php }elseif($_smarty_tpl->tpl_vars['parent']->value=='TOOLS'){?>wrench<?php }?>"></span>&nbsp;<?php echo vtranslate("LBL_".($_smarty_tpl->tpl_vars['parent']->value),$_smarty_tpl->tpl_vars['moduleName']->value);?>
</strong><?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['moduleList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
?><?php $_smarty_tpl->tpl_vars['translatedModuleLabel'] = new Smarty_variable(vtranslate($_smarty_tpl->tpl_vars['moduleModel']->value->get('label'),$_smarty_tpl->tpl_vars['moduleName']->value), null, 0);?><label class="moduleNames"><a id="menubar_item_<?php echo $_smarty_tpl->tpl_vars['moduleName']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getDefaultUrl();?>
"><?php echo $_smarty_tpl->tpl_vars['translatedModuleLabel']->value;?>
</a></label><?php } ?></span><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['more']['last']||($_smarty_tpl->getVariable('smarty')->value['foreach']['more']['index']+1)%4==0){?></div><?php }?><?php } ?><?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?><div class="config-menu"><a id="menubar_item_moduleManager"href="index.php?module=MenuEditor&parent=Settings&view=Index"class="pull-right" title="Customize modules"><span class="fa fa-cog"></span>&nbsp;</a></div><?php }?></div></li></ul></div><div class="span3 marginLeftZero pull-right" id="headerLinks"><span id="headerLinksBig" class="pull-right headerLinksContainer"><div class="span"><a class="dropdown-toggle enable-search" data-toggle="dropdown" href="#" title="Search..."><span class="fa fa-search"></span></a><div class="navbar-search pull-right"><input type="text" class="search-query" id="globalSearchValue" placeholder="Search..."><a class="close-search"><span class="fa fa-times"></span></a></div></div><span class="dropdown span quick-create-icon"><a class="dropdown-toggle" data-toggle="dropdown" href="#" id="menubar_quickCreate" title="Quick Create"><span class="fa fa-plus"></span></a><ul class="dropdown-menu dropdownStyles pull-right" id="quickCreateModules"><?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MENUS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
?><?php if ($_smarty_tpl->tpl_vars['moduleModel']->value->isPermitted('EditView')){?><?php $_smarty_tpl->tpl_vars['quickCreateModule'] = new Smarty_variable($_smarty_tpl->tpl_vars['moduleModel']->value->isQuickCreateSupported(), null, 0);?><?php $_smarty_tpl->tpl_vars['singularLabel'] = new Smarty_variable($_smarty_tpl->tpl_vars['moduleModel']->value->getSingularLabelKey(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['singularLabel']->value=='SINGLE_Calendar'){?><?php $_smarty_tpl->tpl_vars['singularLabel'] = new Smarty_variable('LBL_EVENT_OR_TASK', null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['quickCreateModule']->value=='1'){?><li><a id="menubar_quickCreate_<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getName();?>
"class="quickCreateModule" href="javascript:void(0)"data-name="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getName();?>
"data-url="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getQuickCreateUrl();?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['singularLabel']->value,$_smarty_tpl->tpl_vars['moduleName']->value);?>
</a></li><?php }?><?php }?><?php } ?></ul></span><span class="dropdown span settingIcons"><a class="dropdown-toggle" data-toggle="dropdown" href="#" title="Settings"><span class="fa fa-wrench"></span></a><ul class="dropdown-menu dropdownStyles pull-right config-menu"><?php if ($_smarty_tpl->tpl_vars['USER_MODEL']->value->isAdminUser()){?><li><a href="?module=Ncrm&parent=Settings&view=Index">Setup</a></li><li><a href="?module=Users&parent=Settings&view=List">Manage users</a></li><?php }?><li class="profile"><?php $_smarty_tpl->tpl_vars["profileImages"] = new Smarty_variable($_smarty_tpl->tpl_vars['USER_MODEL']->value->getImageDetails(), null, 0);?><?php $_smarty_tpl->tpl_vars["profileImage"] = new Smarty_variable($_smarty_tpl->tpl_vars['profileImages']->value[0], null, 0);?><div class="profile-item-container clearfix"><div class="profile-picture"style="background-image: url(<?php echo $_smarty_tpl->tpl_vars['profileImage']->value['path'];?>
_<?php echo $_smarty_tpl->tpl_vars['profileImage']->value['orgname'];?>
);"></div><div class="profile-actions"><strong class="profile-username"><?php echo $_smarty_tpl->tpl_vars['USER_MODEL']->value->getDisplayName();?>
</strong><div class="actions"><a class="settings" href="<?php echo $_smarty_tpl->tpl_vars['USER_MODEL']->value->getPreferenceDetailViewUrl();?>
">Settings</a><a class="logout" href="?module=Users&parent=Settings&action=Logout">Log out</a></div></div></div></li></ul></span></span><div id="headerLinksCompact"><span class="btn-group dropdown qCreate cursorPointer"><img src="<?php echo vimage_path('btnAdd_white.png');?>
" class="" alt="<?php echo vtranslate('LBL_QUICK_CREATE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" title="<?php echo vtranslate('LBL_QUICK_CREATE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" data-toggle="dropdown"/><ul class="dropdown-menu dropdownStyles pull-right commonActionsButtonDropDown"><li class="title"><strong><?php echo vtranslate('Quick Create',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></li><hr/><li id="compactquickCreate"><div class="CompactQC"><?php  $_smarty_tpl->tpl_vars['moduleModel'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['moduleModel']->_loop = false;
 $_smarty_tpl->tpl_vars['moduleName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MENUS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['moduleModel']->key => $_smarty_tpl->tpl_vars['moduleModel']->value){
$_smarty_tpl->tpl_vars['moduleModel']->_loop = true;
 $_smarty_tpl->tpl_vars['moduleName']->value = $_smarty_tpl->tpl_vars['moduleModel']->key;
?><?php if ($_smarty_tpl->tpl_vars['moduleModel']->value->isPermitted('EditView')){?><?php $_smarty_tpl->tpl_vars['quickCreateModule'] = new Smarty_variable($_smarty_tpl->tpl_vars['moduleModel']->value->isQuickCreateSupported(), null, 0);?><?php $_smarty_tpl->tpl_vars['singularLabel'] = new Smarty_variable($_smarty_tpl->tpl_vars['moduleModel']->value->getSingularLabelKey(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['quickCreateModule']->value=='1'){?><a class="quickCreateModule" data-name="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getName();?>
"data-url="<?php echo $_smarty_tpl->tpl_vars['moduleModel']->value->getQuickCreateUrl();?>
" href="javascript:void(0)"><?php echo vtranslate($_smarty_tpl->tpl_vars['singularLabel']->value,$_smarty_tpl->tpl_vars['moduleName']->value);?>
</a><?php }?><?php }?><?php } ?></div></li></ul></span><span  class="dropdown"><a class="dropdown-toggle btn-navbar" data-toggle="dropdown" href="#"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></a><ul class="dropdown-menu pull-right"><?php  $_smarty_tpl->tpl_vars['obj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['obj']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['HEADER_LINKS']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["compactIndex"]['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['obj']->key => $_smarty_tpl->tpl_vars['obj']->value){
$_smarty_tpl->tpl_vars['obj']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['obj']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["compactIndex"]['index']++;
?><?php $_smarty_tpl->tpl_vars["src"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getIconPath(), null, 0);?><?php $_smarty_tpl->tpl_vars["icon"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getIcon(), null, 0);?><?php $_smarty_tpl->tpl_vars["title"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getLabel(), null, 0);?><?php $_smarty_tpl->tpl_vars["childLinks"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getChildLinks(), null, 0);?><?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['compactIndex']['index']!=0){?><li class="divider">&nbsp;</li><?php }?><?php  $_smarty_tpl->tpl_vars['obj'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['obj']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['childLinks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['obj']->key => $_smarty_tpl->tpl_vars['obj']->value){
$_smarty_tpl->tpl_vars['obj']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['obj']->key;
?><?php $_smarty_tpl->tpl_vars["id"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getId(), null, 0);?><?php $_smarty_tpl->tpl_vars["href"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getUrl(), null, 0);?><?php $_smarty_tpl->tpl_vars["label"] = new Smarty_variable($_smarty_tpl->tpl_vars['obj']->value->getLabel(), null, 0);?><?php $_smarty_tpl->tpl_vars["onclick"] = new Smarty_variable('', null, 0);?><?php if (stripos($_smarty_tpl->tpl_vars['obj']->value->getUrl(),'javascript:')===0){?><?php $_smarty_tpl->tpl_vars["onclick"] = new Smarty_variable(("onclick=").($_smarty_tpl->tpl_vars['href']->value), null, 0);?><?php $_smarty_tpl->tpl_vars["href"] = new Smarty_variable("javascript:;", null, 0);?><?php }?><li><a target="<?php echo $_smarty_tpl->tpl_vars['obj']->value->target;?>
" id="menubar_item_right_<?php echo Ncrm_Util_Helper::replaceSpaceWithUnderScores($_smarty_tpl->tpl_vars['label']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['label']->value=='Switch to old look'){?>switchLook<?php }?> href="<?php echo $_smarty_tpl->tpl_vars['href']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['onclick']->value;?>
><?php echo vtranslate($_smarty_tpl->tpl_vars['label']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></li><?php } ?><?php } ?></ul></span></div></div></div><div class="clearfix"></div></div></div><?php $_smarty_tpl->tpl_vars["announcement"] = new Smarty_variable($_smarty_tpl->tpl_vars['ANNOUNCEMENT']->value->get('announcement'), null, 0);?><div class="announcement noprint" id="announcement"><marquee direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onMouseOver="this.setAttribute('scrollamount', 0, 0);" OnMouseOut="this.setAttribute('scrollamount', 6, 0);"><?php if (!empty($_smarty_tpl->tpl_vars['announcement']->value)){?><?php echo $_smarty_tpl->tpl_vars['announcement']->value;?>
<?php }else{ ?><?php echo vtranslate('LBL_NO_ANNOUNCEMENTS',$_smarty_tpl->tpl_vars['MODULE']->value);?>
<?php }?></marquee></div><input type='hidden' value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
" id='module' name='module'/><input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['PARENT_MODULE']->value;?>
" id="parent" name='parent' /><input type='hidden' value="<?php echo $_smarty_tpl->tpl_vars['VIEW']->value;?>
" id='view' name='view'/>
<?php }} ?>