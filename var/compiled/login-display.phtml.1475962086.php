<?php /* This file is generated from D:\stats3/template/login/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor3b0ea32fc576cb14aae598a09927117c', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor3b0ea32fc576cb14aae598a09927117c extends lmbMacroTemplateExecutor {
function render($args = array()) {
if($args) extract($args);
$this->_init();
$this->__staticInclude1('_include/page.phtml'); ?>

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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" type="text/css" href="/_/0jrsa6z/media/var/css/styles-main.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript"></script>  
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandlerdb63568f2edeb21cd88d41939fe1704c(array()); ?>    
</head>
<body>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerc57f0996a0d437a0451fbb70bb6ad6da(array()); ?>

<div id="content">
  <?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler734cd943c6a8b6425a4ef4cc890a2efc(array()); ?>

</div>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/0c808bz/media/var/js/6cb7c149f7a462396b254c20436af600.js" ></script>

<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandlerf448bb5397663d94e50d06d387e0bc86(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  
<?php 
}

function __slotHandlerdb63568f2edeb21cd88d41939fe1704c($A= array()) {
if($A) extract($A);
}

function __slotHandlerc57f0996a0d437a0451fbb70bb6ad6da($B= array()) {
if($B) extract($B);
}

function __slotHandler734cd943c6a8b6425a4ef4cc890a2efc($C= array()) {
if($C) extract($C); ?>

  asd
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

function __slotHandlerf448bb5397663d94e50d06d387e0bc86($D= array()) {
if($D) extract($D);
}

function __aslotHandler_js_ready() {

}

}
}
$macro_executor_class='MacroTemplateExecutor3b0ea32fc576cb14aae598a09927117c';