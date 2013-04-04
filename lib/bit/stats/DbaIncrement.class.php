<?php

class DbaIncrement
{
  private $_db;
  private $_max_skip = 50000;
  private $_max_cache = 10000;
  private $_skip;
  private $_cache = array();

  function __construct(DbaWrapper $db)
  {
    $this->_db = $db;
    $this->_skip = $this->_max_skip;
  }

  function increment($key, $count)
  {
    if(!isset($this->_cache[$key]))
    {
      $v = $this->_db->get($key);
      $this->_cache[$key] = is_numeric($v) ? $v : 0; 
    }
    $this->_cache[$key] = bcadd($this->_cache[$key], $count);
    if(0 > $this->_skip--)
      $this->_flush();
  }

  function set($key, $value)
  {
    $this->_cache[$key] = $value;
  }

  function flush()
  {
    $this->_flush();
  }

  private function _flush()
  {
    $this->_skip = $this->_max_skip;
    foreach($this->_cache as $key => $value)
      $this->_db->set($key, $value); 
    $offset = count($this->_cache) - $this->_max_cache;
    if($offset > 0)
      $this->_cache = array_slice($this->_cache, $offset);
  }
  
  function getDb()
  {
    return $this->_db;
  }
}
