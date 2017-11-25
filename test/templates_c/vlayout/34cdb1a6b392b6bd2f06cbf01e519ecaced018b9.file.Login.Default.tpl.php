<?php /* Smarty version Smarty-3.1.7, created on 2017-01-15 13:21:59
         compiled from "E:\MXampp\sites\vtiger.dev\ncrm\includes\runtime/../../layouts/vlayout\modules\Users\Login.Default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9002587b7777409494-58497589%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '34cdb1a6b392b6bd2f06cbf01e519ecaced018b9' => 
    array (
      0 => 'E:\\MXampp\\sites\\vtiger.dev\\ncrm\\includes\\runtime/../../layouts/vlayout\\modules\\Users\\Login.Default.tpl',
      1 => 1484467260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9002587b7777409494-58497589',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'CRM_NAME' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_587b7777588d0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_587b7777588d0')) {function content_587b7777588d0($_smarty_tpl) {?>
<!DOCTYPE html><html><head><title>Ncrm login page</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- for Login page we are added --><link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet"><link href="libraries/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet"><link href="libraries/bootstrap/css/jqueryBxslider.css" rel="stylesheet"/><script src="libraries/jquery/jquery.min.js"></script><script src="libraries/jquery/boxslider/jqueryBxslider.js"></script><script src="libraries/jquery/boxslider/respond.min.js"></script><script>jQuery(document).ready(function () {scrollx = jQuery(window).outerWidth();window.scrollTo(scrollx, 0);slider = jQuery('.bxslider').bxSlider({auto: true,pause: 4000,randomStart: true,autoHover: true});jQuery('.bx-prev, .bx-next, .bx-pager-item').live('click', function () {slider.startAuto();});});</script></head><body><div class="container-fluid login-container"><div class="row-fluid"><div class="span3"><div class="logo"><h1 class="text-logo"><?php echo $_smarty_tpl->tpl_vars['CRM_NAME']->value;?>
</h1></div></div><div class="span9"></div></div><div class="row-fluid"><div class="span12"><div class="content-wrapper"><div class="container-fluid"><div class="row-fluid"><div class="span4"></div><div class="span6"><div class="login-area"><div class="login-box" id="loginDiv"><div class=""><h3 class="login-header">Login</h3></div><form class="form-horizontal login-form" style="margin:0;"action="index.php?module=Users&action=Login" method="POST"><?php if (isset($_REQUEST['error'])){?><div class="alert alert-error"><p>Invalid username or password.</p></div><?php }?><?php if (isset($_REQUEST['fpError'])){?><div class="alert alert-error"><p>Invalid Username or Email address.</p></div><?php }?><?php if (isset($_REQUEST['status'])){?><div class="alert alert-success"><p>Mail has been sent to your inbox, please check your e-mail.</p></div><?php }?><?php if (isset($_REQUEST['statusError'])){?><div class="alert alert-error"><p>Outgoing mail server was not configured.</p></div><?php }?><div class="login-input"><input type="text" id="username" name="username" placeholder="Username"></div><div class="login-input"><input type="password" id="password" name="password"placeholder="Password"></div><div class="login-input" id="forgotPassword"><button type="submit" class="btn btn-success sbutton btn-login">Login</button>&nbsp;&nbsp;&nbsp;<a>Forgot Password?</a></div></form></div><div class="login-box hide" id="forgotPasswordDiv"><form class="form-horizontal login-form" style="margin:0;"action="forgotPassword.php" method="POST"><div class=""><h3 class="login-header">Forgot Password</h3></div><div class="login-input"><input type="text" id="user_name" name="user_name"placeholder="Username"></div><div class="login-input"><input type="text" id="emailId" name="emailId" placeholder="Email"></div><div class="login-input"><div class="controls" id="backButton"><input type="submit" class="btn btn-success sbutton btn-reset" value="Submit" name="retrievePassword" />&nbsp;&nbsp;&nbsp;<a>Back</a></div></div></div></form></div></div></div></div></div></div></div></div></div></body><script>jQuery(document).ready(function () {jQuery("#forgotPassword a").click(function () {jQuery("#loginDiv").hide();jQuery("#forgotPasswordDiv").show();});jQuery("#backButton a").click(function () {jQuery("#loginDiv").show();jQuery("#forgotPasswordDiv").hide();});jQuery("input[name='retrievePassword']").click(function () {var username = jQuery('#user_name').val();var email = jQuery('#emailId').val();var email1 = email.replace(/^\s+/, '').replace(/\s+$/, '');var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;if (username == '') {alert('Please enter valid username');return false;} else if (!emailFilter.test(email1) || email == '') {alert('Please enater valid email address');return false;} else if (email.match(illegalChars)) {alert("The email address contains illegal characters.");return false;} else {return true;}});});</script></html>
<?php }} ?>