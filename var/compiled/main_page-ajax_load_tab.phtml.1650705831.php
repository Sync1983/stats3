<?php /* This file is generated from main_page/ajax_load_tab.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor188938e6df7ea9e41d25419b72f49e91', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor188938e6df7ea9e41d25419b72f49e91 extends lmbMacroTemplateExecutor {
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
echo htmlspecialchars($I,3); ?>"></div>
      <div class="chart_graph"></div>
      <div class="chart-to-full" onclick="window.chart.onFull(<?php $K='';
$L = $item;
if((is_array($L) || ($L instanceof ArrayAccess)) && isset($L['id'])) { $K = $L['id'];
}else{ $K = '';}
echo htmlspecialchars($K,3); ?>,<?php $M='';
$N = $item;
if((is_array($N) || ($N instanceof ArrayAccess)) && isset($N['counter_id'])) { $M = $N['counter_id'];
}else{ $M = '';}
echo htmlspecialchars($M,3); ?>);return false;"></div>
      <div class="chart-toolbox" onclick="showToolbox(this);return false;"></div>
      <div class="toolbox">
        <ul>
          <li onclick="window.chart.changeView(2,<?php $O='';
$P = $item;
if((is_array($P) || ($P instanceof ArrayAccess)) && isset($P['id'])) { $O = $P['id'];
}else{ $O = '';}
echo htmlspecialchars($O,3); ?>);return false;">Линия</li>
          <li onclick="window.chart.changeView(0,<?php $Q='';
$R = $item;
if((is_array($R) || ($R instanceof ArrayAccess)) && isset($R['id'])) { $Q = $R['id'];
}else{ $Q = '';}
echo htmlspecialchars($Q,3); ?>);return false;">Сплайн</li>
          <li onclick="window.chart.changeView(3,<?php $S='';
$T = $item;
if((is_array($T) || ($T instanceof ArrayAccess)) && isset($T['id'])) { $S = $T['id'];
}else{ $S = '';}
echo htmlspecialchars($S,3); ?>);return false;">Область</li>
          <li onclick="window.chart.changeView(1,<?php $U='';
$V = $item;
if((is_array($V) || ($V instanceof ArrayAccess)) && isset($V['id'])) { $U = $V['id'];
}else{ $U = '';}
echo htmlspecialchars($U,3); ?>);return false;">Столбцы</li>
          <li>Экспорт CSV</li>
          <li onclick="window.main.deleteChart(<?php $W='';
$X = $item;
if((is_array($X) || ($X instanceof ArrayAccess)) && isset($X['id'])) { $W = $X['id'];
}else{ $W = '';}
echo htmlspecialchars($W,3); ?>);return false;">Удалить</li>
        </ul>
      </div>
    </li>
    <?php $E++;}if($E > 0) { ?>

  <?php } ?>     
  <li class="chart sortable-disabled" id="chart_add" style="list-style: none;"><h1>Добавьте диаграмму</h1><img src="images/addChart.png" width="200" height="200" style="top:30px;position: relative;"/></li>
</ul>

<script>
  window.chart.addEvents();
  
  function showToolbox(item) {
    var box = $(item).parent().children(".toolbox");
    $(box).css('display','block');
  }
</script><?php 
}

}
}
$macro_executor_class='MacroTemplateExecutor188938e6df7ea9e41d25419b72f49e91';