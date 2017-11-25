{*<!--
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
    <!DOCTYPE html>
    <html>
    <head>
        <title>Ncrm login page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- for Login page we are added -->
        <link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="libraries/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="libraries/bootstrap/css/jqueryBxslider.css" rel="stylesheet"/>
        <script src="libraries/jquery/jquery.min.js"></script>
        <script src="libraries/jquery/boxslider/jqueryBxslider.js"></script>
        <script src="libraries/jquery/boxslider/respond.min.js"></script>
        <script>
            jQuery(document).ready(function () {
                scrollx = jQuery(window).outerWidth();
                window.scrollTo(scrollx, 0);
                slider = jQuery('.bxslider').bxSlider({
                    auto: true,
                    pause: 4000,
                    randomStart: true,
                    autoHover: true
                });
                jQuery('.bx-prev, .bx-next, .bx-pager-item').live('click', function () {
                    slider.startAuto();
                });
            });
        </script>
    </head>
    <body>
    <div class="container-fluid login-container">
        <div class="row-fluid">
            <div class="span3">
                <div class="logo">
                    <h1 class="text-logo">{$CRM_NAME}</h1>
                    {*<img src="layouts/vlayout/skins/images/logo.png">*}
                    {*<br/>*}
                    {*<a target="_blank"*}
                       {*href="http://{$COMPANY_DETAILSCOMPANY_DETAILS.website}">{$COMPANY_DETAILS.name}</a>*}
                </div>
            </div>
            <div class="span9">
                {*<div class="helpLinks">*}
                {*<a href="https://www.ncrm.com">Ncrm Website</a> |*}
                {*<a href="https://wiki.ncrm.com/ncrm6/">Ncrm Wiki</a> |*}
                {*<a href="https://www.ncrm.com/crm/videos/">Ncrm videos </a> |*}
                {*<a href="https://discussions.ncrm.com/">Ncrm Forums</a>*}
                {*</div>*}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="content-wrapper">
                    <div class="container-fluid">
                        <div class="row-fluid">
                            <div class="span4">
                                {*<div class="carousal-container">*}
                                {*<div><h2> Get more out of Ncrm </h2></div>*}
                                {*<ul class="bxslider">*}
                                {*<li>*}
                                {*<div id="slide01" class="slide">*}
                                {*<img class="pull-left" src="{vimage_path('android_text.png')}">*}
                                {*<img class="pull-right" src="{vimage_path('android.png')}"/>*}
                                {*</div>*}
                                {*</li>*}
                                {*<li>*}
                                {*<div id="slide02" class="slide">*}
                                {*<img class="pull-left" src="{vimage_path('iphone_text.png')}"/>*}
                                {*<img class="pull-right" src="{vimage_path('iphone.png')}"/>*}
                                {*</div>*}
                                {*</li>*}
                                {*<li>*}
                                {*<div id="slide03" class="slide">*}
                                {*<img class="pull-left" src="{vimage_path('ipad_text.png')}"/>*}
                                {*<img class="pull-right" src="{vimage_path('ipad.png')}"/>*}
                                {*</div>*}
                                {*</li>*}
                                {*<li>*}
                                {*<div id="slide04" class="slide">*}
                                {*<img class="pull-left" src="{vimage_path('exchange_conn_text.png')}"/>*}
                                {*<img class="pull-right" src="{vimage_path('exchange_conn.png')}"/>*}
                                {*</div>*}
                                {*</li>*}
                                {*<li>*}
                                {*<div id="slide05" class="slide">*}
                                {*<img class="pull-left" src="{vimage_path('outlook_text.png')}"/>*}
                                {*<img class="pull-right" src="{vimage_path('outlook.png')}"/>*}
                                {*</div>*}
                                {*</li>*}
                                {*</ul>*}
                                {*</div>*}
                            </div>
                            <div class="span6">
                                <div class="login-area">
                                    <div class="login-box" id="loginDiv">
                                        <div class="">
                                            <h3 class="login-header">Login</h3>
                                        </div>
                                        <form class="form-horizontal login-form" style="margin:0;"
                                              action="index.php?module=Users&action=Login" method="POST">
                                            {if isset($smarty.request.error)}
                                                <div class="alert alert-error">
                                                    <p>Invalid username or password.</p>
                                                </div>
                                            {/if}
                                            {if isset($smarty.request.fpError)}
                                                <div class="alert alert-error">
                                                    <p>Invalid Username or Email address.</p>
                                                </div>
                                            {/if}
                                            {if isset($smarty.request.status)}
                                                <div class="alert alert-success">
                                                    <p>Mail has been sent to your inbox, please check your e-mail.</p>
                                                </div>
                                            {/if}
                                            {if isset($smarty.request.statusError)}
                                                <div class="alert alert-error">
                                                    <p>Outgoing mail server was not configured.</p>
                                                </div>
                                            {/if}
                                            <div class="login-input">
                                                <input type="text" id="username" name="username" placeholder="Username">
                                            </div>
                                            <div class="login-input">
                                                <input type="password" id="password" name="password"
                                                       placeholder="Password">
                                            </div>
                                            {*<div class="control-group">*}
                                            {*<label class="control-label" for="username"><b>User name</b></label>*}
                                            {*<div class="controls">*}
                                            {**}
                                            {*</div>*}
                                            {*</div>*}

                                            {*<div class="control-group">*}
                                            {*<label class="control-label" for="password"><b>Password</b></label>*}
                                            {*<div class="controls">*}

                                            {*</div>*}
                                            {*</div>*}
                                            <div class="login-input" id="forgotPassword">
                                                <button type="submit" class="btn btn-success sbutton btn-login">Login</button>
                                                &nbsp;&nbsp;&nbsp;<a>Forgot Password?</a>
                                            </div>
                                            {*<div class="control-group signin-button">*}
                                            {*<div class="controls" id="forgotPassword">*}

                                            {*</div>*}
                                            {*</div>*}
                                            {* Retain this tracker to help us get usage details *}
                                            {*<img src='//stats.ncrm.com/stats.php?uid={$APPUNIQUEKEY}&v={$CURRENT_VERSION}&type=U' alt='' title='' border=0 width='1px' height='1px'>*}
                                        </form>
                                        {*<div class="login-subscript">*}
                                        {*<small> Powered by NCRM {$CURRENT_VERSION}</small>*}
                                        {*</div>*}
                                    </div>

                                    <div class="login-box hide" id="forgotPasswordDiv">
                                        <form class="form-horizontal login-form" style="margin:0;"
                                              action="forgotPassword.php" method="POST">
                                            <div class="">
                                                <h3 class="login-header">Forgot Password</h3>
                                            </div>
                                            <div class="login-input">
                                                <input type="text" id="user_name" name="user_name"
                                                       placeholder="Username">
                                            </div>
                                            <div class="login-input">
                                                <input type="text" id="emailId" name="emailId" placeholder="Email">
                                            </div>
                                            <div class="login-input">
                                                <div class="controls" id="backButton">
                                                    <input type="submit" class="btn btn-success sbutton btn-reset" value="Submit" name="retrievePassword" />
                                                    &nbsp;&nbsp;&nbsp;<a>Back</a>
                                                </div>
                                            </div>

                                    </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    {*<div class="navbar navbar-fixed-bottom">*}
    {*<div class="navbar-inner">*}
    {*<div class="container-fluid">*}
    {*<div class="row-fluid">*}
    {*<div class="span6 pull-left" >*}
    {*<div class="footer-content">*}
    {*<small>&#169 2004-{date('Y')}&nbsp;*}
    {*<a href="https://www.ncrm.com"> ncrm.com</a> |*}
    {*<a href="https://www.ncrm.com/LICENSE.txt">Read License</a> |*}
    {*<a href="https://www.ncrm.com/products/crm/privacy_policy.html">Privacy Policy</a> </small>*}
    {*</div>*}
    {*</div>*}
    {*<div class="span6 pull-right" >*}
    {*<div class="pull-right footer-icons">*}
    {*<small>{vtranslate('LBL_CONNECT_WITH_US', $MODULE)}&nbsp;</small>*}
    {*<a href="https://www.facebook.com/ncrm"><img src="layouts/vlayout/skins/images/facebook.png"></a>*}
    {*&nbsp;<a href="https://twitter.com/ncrmcrm"><img src="layouts/vlayout/skins/images/twitter.png"></a>*}
    {*&nbsp;<a href="https://www.linkedin.com/company/ncrm-systems-india-pvt-ltd"><img src="layouts/vlayout/skins/images/linkedin.png"></a>*}
    {*&nbsp;<a href="http://www.youtube.com/user/ncrmcrm"><img src="layouts/vlayout/skins/images/youtube.png"></a>*}
    {*</div>*}
    {*</div>*}
    {*</div>   *}
    {*</div>    *}
    {*</div>   *}
    {*</div>*}
    </body>
    <script>
        jQuery(document).ready(function () {
            jQuery("#forgotPassword a").click(function () {
                jQuery("#loginDiv").hide();
                jQuery("#forgotPasswordDiv").show();
            });

            jQuery("#backButton a").click(function () {
                jQuery("#loginDiv").show();
                jQuery("#forgotPasswordDiv").hide();
            });

            jQuery("input[name='retrievePassword']").click(function () {
                var username = jQuery('#user_name').val();
                var email = jQuery('#emailId').val();

                var email1 = email.replace(/^\s+/, '').replace(/\s+$/, '');
                var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
                var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;

                if (username == '') {
                    alert('Please enter valid username');
                    return false;
                } else if (!emailFilter.test(email1) || email == '') {
                    alert('Please enater valid email address');
                    return false;
                } else if (email.match(illegalChars)) {
                    alert("The email address contains illegal characters.");
                    return false;
                } else {
                    return true;
                }

            });
        });
    </script>
    </html>
{/strip}
