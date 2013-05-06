<?php /* This file is generated from D:\dev\stats3/template/main_page/ajax_add_tab.phtml*/?><?php
if(!class_exists('MacroTemplateExecutor63c82ab2716bf1436554f30f0e192a83', false)){
require_once('limb/macro/src/compiler/lmbMacroTemplateExecutor.class.php');
require_once('limb/macro/src/tags/form/lmbMacroFormWidget.class.php');
require_once('limb/macro/src/tags/form/lmbMacroInputWidget.class.php');
class MacroTemplateExecutor63c82ab2716bf1436554f30f0e192a83 extends lmbMacroTemplateExecutor {

function _init() {
$this->form_add_tab_form = new lmbMacroFormWidget('add_tab_form');
$this->form_add_tab_form->setAttributes(array (
  'method' => 'POST',
  'id' => 'add_tab_form',
));
$this->input_id001 = new lmbMacroInputWidget('name');
$this->input_id001->setAttributes(array (
  'type' => 'text',
  'id' => 'name',
  'style' => 'width:100%;',
));
$this->input_id001->setForm($this->form_add_tab_form);
$this->form_add_tab_form->addChild($this->input_id001);

}
function render($args = array()) {
if($args) extract($args);
$this->_init();
if(isset($this->form_add_tab_form_datasource))$this->form_add_tab_form->setDatasource($this->form_add_tab_form_datasource);
if(isset($this->form_add_tab_form_error_list))$this->form_add_tab_form->setErrorList($this->form_add_tab_form_error_list);
 ?><form<?php $this->form_add_tab_form->renderAttributes(); ?>>       
  <dt>Имя:</dt>
  <dd><input<?php $this->input_id001->renderAttributes(); ?> /></dd>      
</form><?php 
}

}
}
$macro_executor_class='MacroTemplateExecutor63c82ab2716bf1436554f30f0e192a83';