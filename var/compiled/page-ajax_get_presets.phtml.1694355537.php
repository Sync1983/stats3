<?php /* This file is generated from page/ajax_get_presets.phtml*/?><?php
<<<<<<< HEAD
if(!class_exists('MacroTemplateExecutor65673c4bfd9828c592612e4cedcb8fa0', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor65673c4bfd9828c592612e4cedcb8fa0 extends lmbMacroTemplateExecutor {
=======
if(!class_exists('MacroTemplateExecutor31ef293d1fa90fdfab318eab57ff27fb', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
class MacroTemplateExecutor31ef293d1fa90fdfab318eab57ff27fb extends lmbMacroTemplateExecutor {
>>>>>>> 6c40468ef97342a58b5b96ec35c3f6effe95838d
function render($args = array()) {
if($args) extract($args);
$this->_init();
 ?><div>
  Выберите формулу или создайте новую!
  <div>
  Общие:<br>
  <select id="standart_selector" style="width: 90%" class="active-counter-id">    
    <option value="-">---</option>
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
  </div>
  <div>
  Лог:<br>
  <select id="logger_selector" style="width: 90%" class="active-counter-id">
    <option value="-">---</option>
    <?php $Q = 0;$S = $this->logger_names;

if(!is_array($S) && !($S instanceof Iterator) && !($S instanceof IteratorAggregate)) {
$S = array();}
$R = $S;
foreach($R as $item) {if($Q == 0) { ?>

      <?php } ?>

      <option value="<?php $U='';
$V = $item;
if((is_array($V) || ($V instanceof ArrayAccess)) && isset($V['id'])) { $U = $V['id'];
}else{ $U = '';}
echo htmlspecialchars($U,3); ?>"><?php $W='';
$X = $item;
if((is_array($X) || ($X instanceof ArrayAccess)) && isset($X['title'])) { $W = $X['title'];
}else{ $W = '';}
echo htmlspecialchars($W,3); ?></option>
      <?php $Q++;}if($Q > 0) { ?>

    <?php } ?>   
  </select>  
  </div>
</div><?php 
}

}
}
<<<<<<< HEAD
$macro_executor_class='MacroTemplateExecutor65673c4bfd9828c592612e4cedcb8fa0';
=======
$macro_executor_class='MacroTemplateExecutor31ef293d1fa90fdfab318eab57ff27fb';
>>>>>>> 6c40468ef97342a58b5b96ec35c3f6effe95838d
