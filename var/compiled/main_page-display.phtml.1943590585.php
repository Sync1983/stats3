<?php /* This file is generated from D:\dev\stats3/template/main_page/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutorbe4d516bd7bca787705427343c67ce7d', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutorbe4d516bd7bca787705427343c67ce7d extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/chart.js":"\/_\/1q067a7\/js\/chart.js","js\/datepicker.js":"\/_\/0kfb1f0\/js\/datepicker.js","js\/exporting.js":"\/_\/0c4tf0q\/js\/exporting.js","js\/highcharts.js":"\/_\/0c6ekde\/js\/highcharts.js","js\/jquery.easytabs.min.js":"\/_\/1dou8cs\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/0dkm8gl\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/142fxmp\/js\/main.js","js\/md5.js":"\/_\/1x491wy\/js\/md5.js"};</script>  
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" type="text/css" href="/_/02xzc5q/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/1gjss94/media/var/css/styles-tabs.css" />
  <link rel="stylesheet" type="text/css" href="/_/1epp5ns/media/var/css/styles-datepicker.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandlerc43837c3ec8061d7ff1481174f08fffc(array()); ?>    
  <script type="text/javascript" src="/_/1hpx503/media/var/js/5cb39f6d472db3611966f39e29b24901.js" ></script>
  <script>
    var project_id = <?php echo htmlspecialchars($this->project_id,3); ?>;
  </script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandlerade94ff7c552bea66844af541fc68d79(array()); ?>
<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler1535ba8f232979e29888039298664faf(array()); ?>
</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/0nugygb/media/var/js/c7df40071629cacc42e2cf4e4b5ca271.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandler4108e59aaa6d1fabd8163006400416eb(array()); ?>
<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>
});
</script>
</html>

  




  
<?php 
}

function __slotHandlerc43837c3ec8061d7ff1481174f08fffc($A= array()) {
if($A) extract($A);
}

function __slotHandlerade94ff7c552bea66844af541fc68d79($D= array()) {
if($D) extract($D); ?>
  <div id="select-menu">
    <div id="dateRange-block">
      <div id="dateRange-txt" onclick="$('#dateRange-txt').DatePickerShow();">Date</div>
      <div id="dateReload"></div>
    </div>    
    <a id="profile-button" href="#" onclick="var item = $('#user-manager'); if(item.css('display')==='none') { item.css('display','block');} else {item.css('display','none');}; return false;">Профиль</a>
    <div id="user-manager">      
    </div>
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
        
      <?php }if($W == 0) { ?>
          <li class='tab'><a href="#content" id="add_tab" style="text-decoration: none;">Добавьте вкладки</a></li>
        <?php } ?>     
          <li class='tab'><a href="#content" id="add_tab" style="text-decoration: none;">+</a></li>
    </ul>    
  </div>
<?php 
}

function __slotHandler1535ba8f232979e29888039298664faf($BF= array()) {
if($BF) extract($BF); ?> 
<center>
<div id="tab-content">
  <div id="content">
    Please Wait for loading...
  </div>
  <div id="rename-tab" onclick="window.main.renameTabAlert('tabs');"></div>
  <div id="delete-tab" onclick="window.main.deleteTabAlert('tabs');"></div>
</div>
<div class="confirm-delete"></div>
<?php 
}

function __slotHandler4108e59aaa6d1fabd8163006400416eb($BG= array()) {
if($BG) extract($BG);
}

function __aslotHandler_js_ready() {
 ?>
  var bday = new Date(<?php echo htmlspecialchars($this->bday,3); ?>*1000);
  var eday = new Date(<?php echo htmlspecialchars($this->eday,3); ?>*1000);
  $('#tabs').easytabs({
    panelContext:$("#tab-content"),      
  });  
  $('#tabs').bind('easytabs:before',window.main.changeTab);  
  $("#dateRange-txt").DatePicker(
  { 
    flat: false,
    date: [bday,eday],
    calendars: 3,
    mode: "range",   
    starts: 1,
    onChange: function(formated) {         
      $('#dateRange-txt').text(formated.join(' - '));
    },
  });
  
  $("#dateRange-txt").text($.datepicker.formatDate('yy-mm-dd', bday)+' - '+$.datepicker.formatDate('yy-mm-dd', eday));    
  window.main.loadTab();
<?php 
}

}
}
$macro_executor_class='MacroTemplateExecutorbe4d516bd7bca787705427343c67ce7d';