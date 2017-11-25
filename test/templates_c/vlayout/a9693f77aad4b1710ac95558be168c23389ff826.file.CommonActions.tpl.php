<?php /* Smarty version Smarty-3.1.7, created on 2017-11-25 06:05:52
         compiled from "D:\LynxServer\sites\newcrm.dev\fb\includes\runtime/../../layouts/vlayout\modules\Ncrm\CommonActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:192705a190840b6d231-64059150%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9693f77aad4b1710ac95558be168c23389ff826' => 
    array (
      0 => 'D:\\LynxServer\\sites\\newcrm.dev\\fb\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\CommonActions.tpl',
      1 => 1483981283,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '192705a190840b6d231-64059150',
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
  'unifunc' => 'content_5a190840b9752',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a190840b9752')) {function content_5a190840b9752($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars["announcement"] = new Smarty_variable($_smarty_tpl->tpl_vars['ANNOUNCEMENT']->value->get('announcement'), null, 0);?><?php $_smarty_tpl->tpl_vars['count'] = new Smarty_variable(0, null, 0);?><?php $_smarty_tpl->tpl_vars["dateFormat"] = new Smarty_variable($_smarty_tpl->tpl_vars['USER_MODEL']->value->get('date_format'), null, 0);?>
<?php }} ?>