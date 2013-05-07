<?php /* This file is generated from D:\stats3/template/main_page/display.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor0a1155ba899099491e132a41b1ccd646', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor0a1155ba899099491e132a41b1ccd646 extends lmbMacroTemplateExecutor {
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
  <script type="text/javascript">window.ammo=window.ammo||{};window.ammo.file_versions={"js\/chart.js":"\/_\/1198ipz\/js\/chart.js","js\/datepicker.js":"\/_\/0kfb1f0\/js\/datepicker.js","js\/exporting.js":"\/_\/0ms8kqs\/js\/exporting.js","js\/highcharts.js":"\/_\/0s7b9os\/js\/highcharts.js","js\/jquery.easytabs.min.js":"\/_\/0x9fvu4\/js\/jquery.easytabs.min.js","js\/jquery.hashchange.min.js":"\/_\/09oto83\/js\/jquery.hashchange.min.js","js\/main.js":"\/_\/1itfrbw\/js\/main.js","js\/md5.js":"\/_\/0qkk5dg\/js\/md5.js"};</script>  
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/smoothness/jquery-ui.css"></style>
  <link rel="stylesheet" type="text/css" href="/_/1py5wt9/media/var/css/styles-main.css" />  
  <link rel="stylesheet" type="text/css" href="/_/0eqsm52/media/var/css/styles-tabs.css" />
  <link rel="stylesheet" type="text/css" href="/_/1epp5ns/media/var/css/styles-datepicker.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js" type="text/javascript"></script>    
  <?php if(isset($this->__slot_handlers_head)) {foreach($this->__slot_handlers_head as $__slot_handler_head) {call_user_func_array($__slot_handler_head, array(array()));}}$this->__slotHandler3cc8802cf8f5a85bdce7ab13548338d9(array()); ?>    
  <script type="text/javascript" src="/_/0y2gpx9/media/var/js/c91132352bb876119d730b5968b258f6.js" ></script>
  <script>
    var project_id = <?php echo htmlspecialchars($this->project_id,3); ?>;
  </script>
</head>
<body>
<center>
<?php if(isset($this->__slot_handlers_menu)) {foreach($this->__slot_handlers_menu as $__slot_handler_menu) {call_user_func_array($__slot_handler_menu, array(array()));}}$this->__slotHandler17ef70ce93bdcb77bb2bd612fbf90933(array()); ?>

<?php if(isset($this->__slot_handlers_content_wrapper)) {foreach($this->__slot_handlers_content_wrapper as $__slot_handler_content_wrapper) {call_user_func_array($__slot_handler_content_wrapper, array(array()));}}$this->__slotHandler5bcc66c350bcf43c0e17442bfca55e58(array()); ?>

</center>

<script type="text/javascript" src="/_/0d6793j/media/var/i18n/i18n_dictonary.en_US.js" ></script>
<script type="text/javascript" src="/_/1g74if6/media/var/js/0c1edb151553856c18c4a2e3c40eec66.js" ></script>

<div class="ajax-loader" style="display: none;">&nbsp;</div>
<?php if(isset($this->__slot_handlers_js_include)) {foreach($this->__slot_handlers_js_include as $__slot_handler_js_include) {call_user_func_array($__slot_handler_js_include, array(array()));}}$this->__slotHandler3dde9b2ebc0f6655bf4a008f13bdf7ff(array()); ?>

<script type="text/javascript">
jQuery(document).ready(function() {  
  <?php $this->__aslotHandler_js_ready(); ?>

});
</script>
</html>

  




  
<?php 
}

function __slotHandler3cc8802cf8f5a85bdce7ab13548338d9($A= array()) {
if($A) extract($A);
}

function __slotHandler17ef70ce93bdcb77bb2bd612fbf90933($D= array()) {
if($D) extract($D); ?>

  <div id="select-menu">
    <div id="dateRange-block">
      <div id="dateRange-txt" onclick="$('#dateRange-txt').DatePickerShow();">Date</div>
      <div id="dateReload" onclick="window.main.pageReload();"></div>
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

function __slotHandler5bcc66c350bcf43c0e17442bfca55e58($BF= array()) {
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
<div id="main-chart"></div>
<?php 
}

function __slotHandler3dde9b2ebc0f6655bf4a008f13bdf7ff($BG= array()) {
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
$macro_executor_class='MacroTemplateExecutor0a1155ba899099491e132a41b1ccd646';