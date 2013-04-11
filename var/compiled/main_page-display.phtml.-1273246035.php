<?php /* This file is generated from D:\stats3/template/main_page/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor4c2f0e86930d854267e94a8b4a726c7d', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor4c2f0e86930d854267e94a8b4a726c7d extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/bpopup.min.js":"\/_\/1zie26e\/js\/bpopup.min.js","js\/jquery.easytabs.min.js":"\/_\/0x9fvu4\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/09oto83\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/0ul61as\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" type="text/css" href="/_/0e8dect/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/0eqsm52/media/var/css/styles-tabs.css" />  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandlerfed58f0b76aa5583538b2c7e1de80a02(array()); ?>    
  <script type="text/javascript" src="/_/1qi9t3k/media/var/js/6786ac366c0a167bf28d0349284a71bd.js" ></script>
  <script>
    var project_id = <?php echo htmlspecialchars($this->project_id,3); ?>;
  </script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerb1c9d4558cdd15cc03b68f81b02640b6(array()); ?>

<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler22112c7280326bc957396c57a90682f4(array()); ?>

</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1l33lgl/media/var/js/b23c09763e3b0727b423d6a78e533f32.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandlerce7e8b2523bd844e5e05459f0491be6b(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  




  
<?php 
}

function __slotHandlerfed58f0b76aa5583538b2c7e1de80a02($A= array()) {
if($A) extract($A);
}

function __slotHandlerb1c9d4558cdd15cc03b68f81b02640b6($D= array()) {
if($D) extract($D); ?>

  <div id="select-menu">
    <select class="project-selector" onchange="window.main.selectProject(this);return false;">
      <?php $I = 0;$K = $this->projects;

if(!is_array($K) && !($K instanceof Iterator) && !($K instanceof IteratorAggregate)) {
$K = array();}
$J = $K;
foreach($J as $item) {if($I == 0) { ?>

        <?php } ?>

          <option value="<?php $M='';
$N = $item;
if((is_array($N) || ($N instanceof ArrayAccess)) && isset($N['id'])) { $M = $N['id'];
}else{ $M = '';}
echo htmlspecialchars($M,3); ?>" <?php $O='';
$P = $item;
if((is_array($P) || ($P instanceof ArrayAccess)) && isset($P['select'])) { $O = $P['select'];
}else{ $O = '';}
echo htmlspecialchars($O,3); ?>><?php $Q='';
$R = $item;
if((is_array($R) || ($R instanceof ArrayAccess)) && isset($R['title'])) { $Q = $R['title'];
}else{ $Q = '';}
echo htmlspecialchars($Q,3); ?></option>     
        <?php $I++;}if($I > 0) { ?>

      <?php } ?>     
    </select>
  </div>
  <div id="tabs">    
    <ul class='etabs'>
      <?php $W = 0;$Y = $this->tabs;

if(!is_array($Y) && !($Y instanceof Iterator) && !($Y instanceof IteratorAggregate)) {
$Y = array();}
$X = $Y;
foreach($X as $item) {if($W == 0) { ?>

        <?php } ?>          
          <li class='tab'><a href="#content" id="<?php $BB='';
$BC = $item;
if((is_array($BC) || ($BC instanceof ArrayAccess)) && isset($BC['id'])) { $BB = $BC['id'];
}else{ $BB = '';}
echo htmlspecialchars($BB,3); ?>" style="text-decoration: none;"><?php $BD='';
$BE = $item;
if((is_array($BE) || ($BE instanceof ArrayAccess)) && isset($BE['title'])) { $BD = $BE['title'];
}else{ $BD = '';}
echo htmlspecialchars($BD,3); ?></a></li>
        <?php $W++;}if($W > 0) { ?>

      <?php } ?>     
          <li class='tab'><a href="#content" id="add_tab" style="text-decoration: none;">+</a></li>
    </ul>    
  </div>
<?php 
}

function __slotHandler22112c7280326bc957396c57a90682f4($BF= array()) {
if($BF) extract($BF); ?> 
<center>
<div id="tab-content">
  <div id="content">
    Please Wait for loading...
  </div>
</div>
<?php 
}

function __slotHandlerce7e8b2523bd844e5e05459f0491be6b($BG= array()) {
if($BG) extract($BG);
}

function __aslotHandler_js_ready() {
 ?>

  $('#tabs').easytabs({
    panelContext:$("#tab-content"),      
  });
  $('#tabs').bind('easytabs:before',window.main.changeTab);
<?php 
}

}
}
$macro_executor_class='MacroTemplateExecutor4c2f0e86930d854267e94a8b4a726c7d';