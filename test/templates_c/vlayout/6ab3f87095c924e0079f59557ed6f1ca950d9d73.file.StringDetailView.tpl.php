<?php /* Smarty version Smarty-3.1.7, created on 2017-11-25 06:08:50
         compiled from "D:\LynxServer\sites\newcrm.dev\fb\includes\runtime/../../layouts/vlayout\modules\Ncrm\uitypes\StringDetailView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:56845a1908f2c38a27-80733534%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6ab3f87095c924e0079f59557ed6f1ca950d9d73' => 
    array (
      0 => 'D:\\LynxServer\\sites\\newcrm.dev\\fb\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\uitypes\\StringDetailView.tpl',
      1 => 1482050548,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '56845a1908f2c38a27-80733534',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'FIELD_MODEL' => 0,
    'RECORD' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a1908f2c42ce',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a1908f2c42ce')) {function content_5a1908f2c42ce($_smarty_tpl) {?>



<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId(),$_smarty_tpl->tpl_vars['RECORD']->value);?>

<?php }} ?>