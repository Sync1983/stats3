<?php
lmb_require('limb/validation/src/rule/lmbSingleFieldRule.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');

class UniqueFieldRule extends lmbSingleFieldRule
{
  protected $object;

  function __construct($field, $object, $custom_error = '')
  {
    $this->object = $object;
    parent :: __construct($field, $custom_error);
  }

  function check($value)
  {
    $criteria = lmbSQLCriteria :: equal($this->field_name, $value);
    if(!$this->object->isNew())
      $criteria->addAnd(new lmbSQLFieldCriteria('id', $this->object->getId(), lmbSQLFieldCriteria :: NOT_EQUAL));
    $records = lmbActiveRecord :: find($this->object->getClass(), $criteria);
    if($records->count())
      $this->error(lmb_i18n('Значение в поле {Field} уже занято'));
  }
}
