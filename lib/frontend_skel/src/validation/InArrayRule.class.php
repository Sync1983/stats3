<?php
lmb_require('limb/validation/src/rule/lmbSingleFieldRule.class.php');

class InArrayRule extends lmbSingleFieldRule
{
  protected $allow_values = array();

  /**
  * Constructor.
  * @param string Field name
  * @param array List of allow values
  * @param string Custom error message
  */   
  function __construct($field_name, $allow_values, $custom_error = '')
  {
    parent :: __construct($field_name, $custom_error);
    
    $this->allow_values = $allow_values;
  }

  protected function _doValidate($datasource)
  {
    $this->check($datasource->get($this->field_name));
  }

  function check($value)
  { 
    if(!in_array($value, $this->allow_values))
      $this->error('{Field} has not allowed value.');
  }
}
