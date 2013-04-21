<?php /* This file is generated from D:\dev\stats3/template/login/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor9c7dda80f83865cd0e6fd39fa80937dc', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor9c7dda80f83865cd0e6fd39fa80937dc extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/chart.js":"\/_\/1msati8\/js\/chart.js","js\/datepicker.js":"\/_\/0kfb1f0\/js\/datepicker.js","js\/exporting.js":"\/_\/0c4tf0q\/js\/exporting.js","js\/highcharts.js":"\/_\/0c6ekde\/js\/highcharts.js","js\/jquery.easytabs.min.js":"\/_\/1dou8cs\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/0dkm8gl\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/1t7aesp\/js\/main.js","js\/md5.js":"\/_\/1x491wy\/js\/md5.js"};</script>  
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" type="text/css" href="/_/02xzc5q/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/1gjss94/media/var/css/styles-tabs.css" />
  <link rel="stylesheet" type="text/css" href="/_/1epp5ns/media/var/css/styles-datepicker.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandler15577b3986358f8f5b2fe9ea3723ea61(array()); ?>    
  <script type="text/javascript" src="/_/1hpx503/media/var/js/5cb39f6d472db3611966f39e29b24901.js" ></script>
  <script>
    var project_id = <?php echo htmlspecialchars($this->project_id,3); ?>;
  </script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerda2928b5b4868506206f3f5eb41465d6(array()); ?>
<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler09bd7dfc10aee6e1cf464eac9f248a65(array()); ?>
</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1plguc7/media/var/js/c7df40071629cacc42e2cf4e4b5ca271.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandler44c170ba7c2a0b2121387df66ddf305b(array()); ?>
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

function __slotHandler15577b3986358f8f5b2fe9ea3723ea61($A= array()) {
if($A) extract($A);
}

function __slotHandlerda2928b5b4868506206f3f5eb41465d6($D= array()) {
if($D) extract($D);
}

function __slotHandler09bd7dfc10aee6e1cf464eac9f248a65($E= array()) {
if($E) extract($E);
}

function __slotHandler44c170ba7c2a0b2121387df66ddf305b($F= array()) {
if($F) extract($F);
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
$macro_executor_class='MacroTemplateExecutor9c7dda80f83865cd0e6fd39fa80937dc';