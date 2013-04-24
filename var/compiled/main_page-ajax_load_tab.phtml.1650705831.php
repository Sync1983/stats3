<?php /* This file is generated from main_page/ajax_load_tab.phtml*/?><?php
if(!class_exists('MacroTemplateExecutore5b350590e86675b450edbc8a147a775', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutore5b350590e86675b450edbc8a147a775 extends lmbMacroTemplateExecutor {
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
echo htmlspecialchars($I,3); ?>"><div class="delete-chart" onclick="window.main.deleteChart(<?php $K='';
$L = $item;
if((is_array($L) || ($L instanceof ArrayAccess)) && isset($L['id'])) { $K = $L['id'];
}else{ $K = '';}
echo htmlspecialchars($K,3); ?>);return false;"></div><div class="chart_graph"></div></div>
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
$macro_executor_class='MacroTemplateExecutore5b350590e86675b450edbc8a147a775';