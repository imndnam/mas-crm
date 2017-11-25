<?php /* Smarty version Smarty-3.1.7, created on 2017-11-25 06:14:23
         compiled from "D:\LynxServer\sites\newcrm.dev\fb\includes\runtime/../../layouts/vlayout\modules\Ncrm\EditViewActions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:93875a190a3f1ca1d5-89590688%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61f2145da6f18602b5dd943ee909bc64afa02bd8' => 
    array (
      0 => 'D:\\LynxServer\\sites\\newcrm.dev\\fb\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\EditViewActions.tpl',
      1 => 1484046261,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '93875a190a3f1ca1d5-89590688',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a190a3f1d1cc',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a190a3f1d1cc')) {function content_5a190a3f1d1cc($_smarty_tpl) {?>

<div class="row-fluid edit-bottom-toolbar"><div class="pull-right"><button class="btn btn-success" type="submit"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button><a class="" type="reset" onclick="javascript:window.history.back();"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div><div class="clearfix"></div></div><br></form></div><?php }} ?>