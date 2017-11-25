<?php /* Smarty version Smarty-3.1.7, created on 2017-10-26 15:04:03
         compiled from "E:\MXampp\sites\vtiger.dev\ncrm\includes\runtime/../../layouts/vlayout\modules\Ncrm\ModalFooter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:507059f1f963507972-94304413%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c0242a885db08c3d1a7c70a97ca028162864d73' => 
    array (
      0 => 'E:\\MXampp\\sites\\vtiger.dev\\ncrm\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\ModalFooter.tpl',
      1 => 1482050599,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '507059f1f963507972-94304413',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_59f1f96351f54',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59f1f96351f54')) {function content_59f1f96351f54($_smarty_tpl) {?>
<div class="modal-footer"><div class="pull-right cancelLinkContainer" style="margin-top:0px;"><a class="cancelLink" type="reset" data-dismiss="modal"><?php echo vtranslate('LBL_CANCEL',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</a></div><button class="btn btn-success" type="submit" name="saveButton"><strong><?php echo vtranslate('LBL_SAVE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</strong></button></div><?php }} ?>