<?php /* Smarty version Smarty-3.1.7, created on 2017-01-15 13:22:07
         compiled from "E:\MXampp\sites\vtiger.dev\ncrm\includes\runtime/../../layouts/vlayout\modules\Ncrm\CommonActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:32757587b777f7152f1-19738549%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6c2a5eaa3a4ed1fa36a5041e084d644cb6f1f0c' => 
    array (
      0 => 'E:\\MXampp\\sites\\vtiger.dev\\ncrm\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\CommonActions.tpl',
      1 => 1483981283,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32757587b777f7152f1-19738549',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ANNOUNCEMENT' => 0,
    'USER_MODEL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_587b777f7cbc6',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b777f7cbc6')) {function content_587b777f7cbc6($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars["announcement"] = new Smarty_variable($_smarty_tpl->tpl_vars['ANNOUNCEMENT']->value->get('announcement'), null, 0);?><?php $_smarty_tpl->tpl_vars['count'] = new Smarty_variable(0, null, 0);?><?php $_smarty_tpl->tpl_vars["dateFormat"] = new Smarty_variable($_smarty_tpl->tpl_vars['USER_MODEL']->value->get('date_format'), null, 0);?>
<?php }} ?>