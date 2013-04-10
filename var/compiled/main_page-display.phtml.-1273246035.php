<?php /* This file is generated from D:\stats3/template/main_page/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutord74f9e8f1423d216ffeb34bbac6adc2e', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutord74f9e8f1423d216ffeb34bbac6adc2e extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/bpopup.min.js":"\/_\/1zie26e\/js\/bpopup.min.js","js\/jquery.easytabs.js":"\/_\/0ksb7k7\/js\/jquery.easytabs.js","js\/jquery.easytabs.min.js":"\/_\/0x9fvu4\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/09oto83\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/1qqoblm\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" type="text/css" href="/_/1b47l2j/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/15av1kw/media/var/css/styles-tabs.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandlerff68a69ed452d8a974f27f22781db8e1(array()); ?>    
  <script type="text/javascript" src="/_/08e2sdi/media/var/js/3d234c9dc946dbe050e000960924e2f7.js" ></script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandler75902479aa6fa25344418f95b146d816(array()); ?>

<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandlerb69e58c173b9cbeb24c9c9128b6b4443(array()); ?>

</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/07qdmh9/media/var/js/b23c09763e3b0727b423d6a78e533f32.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandler1d196d567d72bbb658a1312655c8135e(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  



  
  
<?php 
}

function __slotHandlerff68a69ed452d8a974f27f22781db8e1($A= array()) {
if($A) extract($A);
}

function __slotHandler75902479aa6fa25344418f95b146d816($B= array()) {
if($B) extract($B); ?>

  <div id="select-menu">
    <select class="project-selector">
      <?php $G = 0;$I = $this->projects;

if(!is_array($I) && !($I instanceof Iterator) && !($I instanceof IteratorAggregate)) {
$I = array();}
$H = $I;
foreach($H as $item) {if($G == 0) { ?>

        <?php } ?>

          <option value="<?php $K='';
$L = $item;
if((is_array($L) || ($L instanceof ArrayAccess)) && isset($L['id'])) { $K = $L['id'];
}else{ $K = '';}
echo htmlspecialchars($K,3); ?>"><?php $M='';
$N = $item;
if((is_array($N) || ($N instanceof ArrayAccess)) && isset($N['title'])) { $M = $N['title'];
}else{ $M = '';}
echo htmlspecialchars($M,3); ?></option>     
        <?php $G++;}if($G > 0) { ?>

      <?php } ?>     
    </select>
  </div>
  <div id="tabs">    
    <ul class='etabs'>
      <li class='tab' class="active"><a href="#content">HTML Markup</a></li>
      <li class='tab'><a href="#content">Required JS</a></li>        
    </ul>    
  </div>
<?php 
}

function __slotHandlerb69e58c173b9cbeb24c9c9128b6b4443($O= array()) {
if($O) extract($O); ?> 
<center>
<div id="tab-content">
  <div id="content">
    Please Wait for loading...
  </div>
</div>
<?php 
}

function __slotHandler1d196d567d72bbb658a1312655c8135e($P= array()) {
if($P) extract($P);
}

function __aslotHandler_js_ready() {
 ?>

    $('#tabs').easytabs({
      panelContext:$("#tab-content")
    });
    //window.main.loadMenu(<?php echo $this->tabs; ?>,"tabs");
  <?php 
}

}
}
$macro_executor_class='MacroTemplateExecutord74f9e8f1423d216ffeb34bbac6adc2e';