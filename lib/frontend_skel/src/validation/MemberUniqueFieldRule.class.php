<?php

lmb_require('limb/validation/src/rule/lmbSingleFieldRule.class.php');
lmb_require('limb/dbal/src/criteria/lmbSQLFieldCriteria.class.php');

class MemberUniqueFieldRule extends lmbSingleFieldRule
{
  protected $current_agent;

  function __construct($field, $current_agent, $custom_error = '')
  {
    $this->current_agent = $current_agent;
    parent :: __construct($field, $custom_error);
  }

  function check($value)
  {

    $criteria = new lmbSQLFieldCriteria($this->field_name, $value);
    if(!$this->current_agent->isNew())
      $criteria->addAnd(new lmbSQLFieldCriteria('id',
                                                $this->current_agent->getId(),
                                                lmbSQLFieldCriteria :: NOT_EQUAL));

    $records = lmbActiveRecord :: find('Member', $criteria);

    if($records->count())
      $this->error(lmb_i18n('Значение в поле {Field} уже занято другим пользователем'));
  }
}
