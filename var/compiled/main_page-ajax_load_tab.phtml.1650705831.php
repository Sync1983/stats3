<?php /* This file is generated from main_page/ajax_load_tab.phtml*/?><?php
if(!class_exists('MacroTemplateExecutorba55b6746982250eb2ba8e3bfb61dfcb', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutorba55b6746982250eb2ba8e3bfb61dfcb extends lmbMacroTemplateExecutor {
function render($args = array()) {
if($args) extract($args);
$this->_init();
 ?><ul style="margin-left: 10px;padding: 0;" id="main-view">
  <?php $E = 0;$G = $this->charts;

if(!is_array($G) && !($G instanceof Iterator) && !($G instanceof IteratorAggregate)) {
$G = array();}
$F = $G;
foreach($F as $item) {if($E == 0) { ?>

    <?php } ?>

    <li class="chart" id="chart_<?php $I='';
$J = $item;
if((is_array($J) || ($J instanceof ArrayAccess)) && isset($J['id'])) { $I = $J['id'];
}else{ $I = '';}
echo htmlspecialchars($I,3); ?>"><div class="delete-chart" onclick="window.main.deleteChart(<?php $K='';
$L = $item;
if((is_array($L) || ($L instanceof ArrayAccess)) && isset($L['id'])) { $K = $L['id'];
}else{ $K = '';}
echo htmlspecialchars($K,3); ?>);return false;"></div><div class="chart_graph"></div></li>
    <?php $E++;}if($E > 0) { ?>

  <?php } ?>     
  <li class="chart sortable-disabled" id="chart_add" style="list-style: none;"><h1>Добавьте диаграмму</h1><img src="images/addChart.png" width="200" height="200" style="top:30px;position: relative;"/></li>
</ul>

<script>
  window.chart.addEvents();
</script><?php 
}

}
}
$macro_executor_class='MacroTemplateExecutorba55b6746982250eb2ba8e3bfb61dfcb';