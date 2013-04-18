<?php /* This file is generated from D:\stats3/template/not_found.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor6e10274040ec560ba01842d61fc538a8', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor6e10274040ec560ba01842d61fc538a8 extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/chart.js":"\/_\/1wv1ozt\/js\/chart.js","js\/datepicker.js":"\/_\/0kfb1f0\/js\/datepicker.js","js\/exporting.js":"\/_\/0ms8kqs\/js\/exporting.js","js\/highcharts.js":"\/_\/0s7b9os\/js\/highcharts.js","js\/jquery.easytabs.min.js":"\/_\/0x9fvu4\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/09oto83\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/143vs50\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" type="text/css" href="/_/1qez7vt/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/0eqsm52/media/var/css/styles-tabs.css" />
  <link rel="stylesheet" type="text/css" href="/_/1epp5ns/media/var/css/styles-datepicker.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandler7f445a24d13fb6f67d8fa55747fca682(array()); ?>    
  <script type="text/javascript" src="/_/0y2gpx9/media/var/js/c91132352bb876119d730b5968b258f6.js" ></script>
  <script>
    var project_id = <?php echo htmlspecialchars($this->project_id,3); ?>;
  </script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandler6600216c405640fad94f7f4c3da293e6(array()); ?>

<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandlere738e83abe8719066bd40b62c2804f65(array()); ?>

</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/0h6lcc9/media/var/js/0c1edb151553856c18c4a2e3c40eec66.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandlercecd66a4e1e39457749e72b82a84dffa(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  
<?php 
}

function __slotHandler7f445a24d13fb6f67d8fa55747fca682($A= array()) {
if($A) extract($A);
}

function __slotHandler6600216c405640fad94f7f4c3da293e6($D= array()) {
if($D) extract($D);
}

function __slotHandlere738e83abe8719066bd40b62c2804f65($E= array()) {
if($E) extract($E); ?>

    <h2>404 - Not found</h2>
  <?php 
}

function __slotHandlercecd66a4e1e39457749e72b82a84dffa($F= array()) {
if($F) extract($F);
}

function __aslotHandler_js_ready() {

}

}
}
$macro_executor_class='MacroTemplateExecutor6e10274040ec560ba01842d61fc538a8';