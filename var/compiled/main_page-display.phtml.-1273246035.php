<?php /* This file is generated from D:\stats3/template/main_page/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor4f8e1bc31a72bbbb3e6c5d1adb055459', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor4f8e1bc31a72bbbb3e6c5d1adb055459 extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/bpopup.min.js":"\/_\/1zie26e\/js\/bpopup.min.js","js\/main.js":"\/_\/1v28l4p\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" type="text/css" href="/_/15opygu/media/var/css/styles-main.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript"></script>  
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandler3ef77b214c43e8f1b43745da52f4b670(array()); ?>    
  <script type="text/javascript" src="/_/1ad11hz/media/var/js/c1cbeae377b511cf1acbd60e83a3d9b4.js" ></script>
</head>
<body>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandler8a6b1fb00984833d3419d185182f68cf(array()); ?>

<div id="content">
  <?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler87ae628712589aeb10cecbc1623e5670(array()); ?>

</div>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1aud537/media/var/js/b23c09763e3b0727b423d6a78e533f32.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandler6127694aa0137fb90c19bf085d79ccdc(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  
<?php 
}

function __slotHandler3ef77b214c43e8f1b43745da52f4b670($A= array()) {
if($A) extract($A);
}

function __slotHandler8a6b1fb00984833d3419d185182f68cf($B= array()) {
if($B) extract($B);
}

function __slotHandler87ae628712589aeb10cecbc1623e5670($C= array()) {
if($C) extract($C);
}

function __slotHandler6127694aa0137fb90c19bf085d79ccdc($D= array()) {
if($D) extract($D);
}

function __aslotHandler_js_ready() {

}

}
}
$macro_executor_class='MacroTemplateExecutor4f8e1bc31a72bbbb3e6c5d1adb055459';