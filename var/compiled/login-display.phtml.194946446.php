<?php /* This file is generated from login/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor5b28f2260a0382f1072fffb59ba66109', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor5b28f2260a0382f1072fffb59ba66109 extends lmbMacroTemplateExecutor {
function render($args = array()) {
if($args) extract($args);
$this->_init();
$this->__staticInclude1('_include/page.phtml'); ?>


<script>
  window.main.showLoader();
</script>
<?php 
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/main.js":"\/_\/1bex2gz\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" type="text/css" href="/_/1ynkzj9/media/var/css/styles-main.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript"></script>  
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandler2ed057085ffec797835a33c8d40fa84f(array()); ?>    
</head>
<body>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerd1a2c275b95e6cc46a728cad45bcbb79(array()); ?>

<div id="content">
  <?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandleraf14975b32a68767561870a467654152(array()); ?>

</div>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1y36c7d/media/var/js/782352eaf3a268c6779a85e17b196815.js" ></script>

<div class="-ammo-ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandlerf831fc0e520937bac24de1aa6cb01829(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  
<?php 
}

function __slotHandler2ed057085ffec797835a33c8d40fa84f($A= array()) {
if($A) extract($A);
}

function __slotHandlerd1a2c275b95e6cc46a728cad45bcbb79($B= array()) {
if($B) extract($B);
}

function __slotHandleraf14975b32a68767561870a467654152($C= array()) {
if($C) extract($C); ?>  
    <form id="-js-login-form" method="post">
      <dl>
      <dt><label for="login">Логин</label></dt>
      <dd><input type="text" name="login" id="login" /></dd>
      <dt><label for="password">Пароль</label></dt>
      <dd><input type="password" name="password" id="password" /></dd>
      </dl>
      <input id="-js-submit" type="submit" value="Войти" disabled="disabled" />
    </form>
  <?php 
}

function __slotHandlerf831fc0e520937bac24de1aa6cb01829($D= array()) {
if($D) extract($D);
}

function __aslotHandler_js_ready() {

}

}
}
$macro_executor_class='MacroTemplateExecutor5b28f2260a0382f1072fffb59ba66109';