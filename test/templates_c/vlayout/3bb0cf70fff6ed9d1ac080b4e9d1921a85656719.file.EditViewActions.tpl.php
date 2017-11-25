<?php /* Smarty version Smarty-3.1.7, created on 2017-10-26 15:04:41
         compiled from "E:\MXampp\sites\vtiger.dev\ncrm\includes\runtime/../../layouts/vlayout\modules\Ncrm\EditViewActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:755759f1f989a70ba7-80924930%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3bb0cf70fff6ed9d1ac080b4e9d1921a85656719' => 
    array (
      0 => 'E:\\MXampp\\sites\\vtiger.dev\\ncrm\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\EditViewActions.tpl',
      1 => 1484046261,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '755759f1f989a70ba7-80924930',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_59f1f989ab59e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59f1f989ab59e')) {function content_59f1f989ab59e($_smarty_tpl) {?>

<div class="row-fluid edit-bottom-toolbar"><div class="pull-right"><button class="btn btn-success" type="submit"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><a class="" type="reset" onclick="javascript:window.history.back();"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div><div class="clearfix"></div></div><br></form></div><?php }} ?>