<?php /* This file is generated from D:\stats3/template/login/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutorcf824828007eaff1488e646204bdbfae', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutorcf824828007eaff1488e646204bdbfae extends lmbMacroTemplateExecutor {
function render($args = array()) {
if($args) extract($args);
$this->_init();
$this->__staticInclude1('_include/page.phtml');
}

function __staticInclude1($file) {
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <base href="<?php echo LIMB_HTTP_BASE_PATH?>" />  
  <script type="text/javascript">
    window.myjs = window.myjs || {}; 
    window.myjs.server_vars = <?php echo json_encode($this->toolkit->getJsVars())?>
  </script>  
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/bpopup.min.js":"\/_\/1zie26e\/js\/bpopup.min.js","js\/main.js":"\/_\/1v28l4p\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" type="text/css" href="/_/15opygu/media/var/css/styles-main.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript"></script>  
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandler1f8887f0c88059fd890c196b2e87b893(array()); ?>    
  <script type="text/javascript" src="/_/1ad11hz/media/var/js/c1cbeae377b511cf1acbd60e83a3d9b4.js" ></script>
</head>
<body>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerbd24008e35e0020a6207654598276da5(array()); ?>

<div id="content">
  <?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler825363c956092c411e163be38ed0741b(array()); ?>

</div>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1aud537/media/var/js/b23c09763e3b0727b423d6a78e533f32.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandlere7345bde4ddd29bfe2d737026a90d685(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

<center>
  <p style="vertical-align: central;">Please Login first!</p>
</center>
<div id="login_form">
  <form id="-js-login-form" method="post">
    <dl>
      <dt><label for="login">Логин</label></dt>
      <dd><input class="textfield" type="text" name="login" id="login" /></dd>
      <dt><label for="password">Пароль</label></dt>
      <dd><input class="textfield" type="password" name="password" id="password" /></dd>
    </dl>
    <button id="-js-submit" onclick="window.main.loginClick('login_form');return false;">Войти</button>
    <p class="errors" style="display: none">Ошибки</p>
  </form>
  
</div>

  
<?php 
}

function __slotHandler1f8887f0c88059fd890c196b2e87b893($A= array()) {
if($A) extract($A);
}

function __slotHandlerbd24008e35e0020a6207654598276da5($B= array()) {
if($B) extract($B);
}

function __slotHandler825363c956092c411e163be38ed0741b($C= array()) {
if($C) extract($C);
}

function __slotHandlere7345bde4ddd29bfe2d737026a90d685($D= array()) {
if($D) extract($D);
}

function __aslotHandler_js_ready() {
 ?>

    $('#login_form').bPopup({
            modalClose: true,
            opacity: 0.5,            
        });  
  <?php 
}

}
}
$macro_executor_class='MacroTemplateExecutorcf824828007eaff1488e646204bdbfae';