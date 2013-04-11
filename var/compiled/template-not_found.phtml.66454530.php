<?php /* This file is generated from D:\stats3/template/not_found.phtml*/?><?php
if(!class_exists('MacroTemplateExecutore1af44e43fee53949cd3f8d82b060db3', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutore1af44e43fee53949cd3f8d82b060db3 extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/bpopup.min.js":"\/_\/1zie26e\/js\/bpopup.min.js","js\/jquery.easytabs.min.js":"\/_\/0x9fvu4\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/09oto83\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/0kox7dt\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" type="text/css" href="/_/0e8dect/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/0eqsm52/media/var/css/styles-tabs.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandlerc6294f6e8508651a960bd48a4eb87362(array()); ?>    
  <script type="text/javascript" src="/_/1qi9t3k/media/var/js/6786ac366c0a167bf28d0349284a71bd.js" ></script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerae465f03fbc86f16a39f2b13058c315a(array()); ?>

<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler238a85f208613b7eae7489f786e6f74c(array()); ?>

</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1armejm/media/var/js/b23c09763e3b0727b423d6a78e533f32.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandlerd3ddadedd3409d7fa374861045a297b5(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  
<?php 
}

function __slotHandlerc6294f6e8508651a960bd48a4eb87362($A= array()) {
if($A) extract($A);
}

function __slotHandlerae465f03fbc86f16a39f2b13058c315a($B= array()) {
if($B) extract($B);
}

function __slotHandler238a85f208613b7eae7489f786e6f74c($C= array()) {
if($C) extract($C); ?>

    <h2>404 - Not found</h2>
  <?php 
}

function __slotHandlerd3ddadedd3409d7fa374861045a297b5($D= array()) {
if($D) extract($D);
}

function __aslotHandler_js_ready() {

}

}
}
$macro_executor_class='MacroTemplateExecutore1af44e43fee53949cd3f8d82b060db3';