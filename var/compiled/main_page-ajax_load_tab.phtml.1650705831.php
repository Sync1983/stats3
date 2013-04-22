<?php /* This file is generated from main_page/ajax_load_tab.phtml*/?><?php
<<<<<<< HEAD
if(!class_exists('MacroTemplateExecutorfd222add805cb9b27260eeacaa34c1b6', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutorfd222add805cb9b27260eeacaa34c1b6 extends lmbMacroTemplateExecutor {
=======
if(!class_exists('MacroTemplateExecutor84678fbac2ceafe64b7542099b0e0307', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor84678fbac2ceafe64b7542099b0e0307 extends lmbMacroTemplateExecutor {
>>>>>>> 05a48be3b5f56c7502cbb94143a52384b8876dc7
function render($args = array()) {
if($args) extract($args);
$this->_init();
 ?><div style="margin-left: 10px;padding: 0;">
  <?php $E = 0;$G = $this->charts;

if(!is_array($G) && !($G instanceof Iterator) && !($G instanceof IteratorAggregate)) {
$G = array();}
$F = $G;
foreach($F as $item) {if($E == 0) { ?>
    <?php } ?>
    <div class="chart" id="chart_<?php $I='';
$J = $item;
if((is_array($J) || ($J instanceof ArrayAccess)) && isset($J['id'])) { $I = $J['id'];
}else{ $I = '';}
echo htmlspecialchars($I,3); ?>"><div class="delete-chart"></div><div class="chart_graph"></div></div>
    <?php $E++;}if($E > 0) { ?>
  <?php } ?>     
    <div class="chart" id="chart_add"><h1>Добавьте диаграмму</h1><img src="images/addChart.png" width="200" height="200" style="top:30px;position: relative;"/></div>
</div>

<script>
  window.chart.addEvents();
</script><?php 
}

}
}
<<<<<<< HEAD
$macro_executor_class='MacroTemplateExecutorfd222add805cb9b27260eeacaa34c1b6';
=======
$macro_executor_class='MacroTemplateExecutor84678fbac2ceafe64b7542099b0e0307';
>>>>>>> 05a48be3b5f56c7502cbb94143a52384b8876dc7
