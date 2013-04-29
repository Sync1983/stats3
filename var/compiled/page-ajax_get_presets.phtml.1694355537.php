<?php /* This file is generated from page/ajax_get_presets.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor05ed293f6e1aa91881f4349e09c4a4e8', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor05ed293f6e1aa91881f4349e09c4a4e8 extends lmbMacroTemplateExecutor {
function render($args = array()) {
if($args) extract($args);
$this->_init();
 ?><div>
  Выберите формулу или создайте новую!
  <select style="width: 90%" class="active-counter-id">    
    <?php $E = 0;$G = $this->names;

if(!is_array($G) && !($G instanceof Iterator) && !($G instanceof IteratorAggregate)) {
$G = array();}
$F = $G;
foreach($F as $item) {if($E == 0) { ?>

      <?php } ?>

      <option value="<?php $I='';
$J = $item;
if((is_array($J) || ($J instanceof ArrayAccess)) && isset($J['id'])) { $I = $J['id'];
}else{ $I = '';}
echo htmlspecialchars($I,3); ?>"><?php $K='';
$L = $item;
if((is_array($L) || ($L instanceof ArrayAccess)) && isset($L['title'])) { $K = $L['title'];
}else{ $K = '';}
echo htmlspecialchars($K,3); ?></option>
      <?php $E++;}if($E > 0) { ?>

    <?php } ?>   
  </select>
</div><?php 
}

}
}
$macro_executor_class='MacroTemplateExecutor05ed293f6e1aa91881f4349e09c4a4e8';