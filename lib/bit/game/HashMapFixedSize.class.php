<?php

class HashMapFixedSize
{
  protected $_size = 0;
  protected $_max_size;
  protected $_map = array();

  function __construct($max_size)  
  {
    $this->_max_size = $max_size;  
  }

  function set($key, $value)
  {
    if(!isset($this->_map[$key]))
    {
      if($this->_size == $this->_max_size)
      {
        reset($this->_map);
        unset($this->_map[key($this->_map)]);
      }
      else
        $this->_size++;
    }
    $this->_map[$key] = $value;
  }

  function get($key)
  {
    if(isset($this->_map[$key]))
      return $this->_map[$key];
    return null;
  }
}
