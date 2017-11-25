<?php /* Smarty version Smarty-3.1.7, created on 2017-11-25 06:21:52
         compiled from "D:\LynxServer\sites\newcrm.dev\fb\includes\runtime/../../layouts/vlayout\modules\Ncrm\DetailViewFullContents.tpl" */ ?>
<?php /*%%SmartyHeaderCode:262605a190c00a05fb2-40809235%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '11605f7ad4e60c0b67047a44d8d25227fc34edc3' => 
    array (
      0 => 'D:\\LynxServer\\sites\\newcrm.dev\\fb\\includes\\runtime/../../layouts/vlayout\\modules\\Ncrm\\DetailViewFullContents.tpl',
      1 => 1482050579,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '262605a190c00a05fb2-40809235',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
    'RECORD_STRUCTURE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5a190c00a325a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5a190c00a325a')) {function content_5a190c00a325a($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('DetailViewBlockView.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('RECORD_STRUCTURE'=>$_smarty_tpl->tpl_vars['RECORD_STRUCTURE']->value,'MODULE_NAME'=>$_smarty_tpl->tpl_vars['MODULE_NAME']->value), 0);?>
<?php }} ?>