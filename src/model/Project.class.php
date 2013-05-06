<?php

class Project extends lmbActiveRecord
{
  protected $_has_many = array(
    'counters' => array(
      'field' => 'project_id',
      'class' => 'Counter',
    ),
    'charts' => array(
      'field' => 'project_id',
      'class' => 'Chart',
    ),
    'referrers' => array(
      'field' => 'project_id',
      'class' => 'Referrer',
    ),
  );

  protected function _onBeforeCreate()
  {
    if(!$this->get('api_key'))
      $this->regenApiKey();
  }

  function regenApiKey()
  {
    $key = '';
    while(strlen($key) < 32)
      $key .= base_convert(abs(crc32(microtime())), 10, 32);
    $this->set('api_key', substr($key, 0, 32));
  }
}
