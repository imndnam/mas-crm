<?php /* Smarty version Smarty-3.1.7, created on 2017-11-25 06:06:00
         compiled from "D:\LynxServer\sites\newcrm.dev\fb\includes\runtime/../../layouts/vlayout\modules\Ncrm\SideBar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14285a1908481151b1-79714989%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '49380189f771027f67c00307b0f34f544f3ed33b' => 
    array (
      0 => 'D:\\LynxServer\\sites\\newcrm.dev\\fb\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\SideBar.tpl',
      1 => 1482050547,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14285a1908481151b1-79714989',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a19084811f49',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a19084811f49')) {function content_5a19084811f49($_smarty_tpl) {?>
<div class="sideBarContents"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarLinks.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<div class="clearfix"></div><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('SideBarWidgets.tpl',$_smarty_tpl->tpl_vars['MODULE']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
</div><?php }} ?>