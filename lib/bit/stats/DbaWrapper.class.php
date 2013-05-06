<?php

class DbaWrapper
{
  protected $_res;
  protected $_file;

  function __construct($file, $type = 'db4', $mode = 'c')
  {
    $this->_file = $file;
    $this->_res = dba_popen($file, $mode, $type);
    if(!$this->_res)
      throw new Exception('Failed open db '.$file. ' and type '.$type);
  }

  function get($key)
  {
    return dba_fetch($key, $this->_res);  
  }

  function has($key)
  {
    return dba_exists($key, $this->_res);
  }

  function set($key, $value)
  {
    if(!dba_replace($key, $value, $this->_res))
      throw new Exception('Failed set value '.$this->_file);
  }

  function add($key, $value)
  {
    return dba_insert($key, $value, $this->_res);
  }

  function getConnection()
  {
    return $this->_res;
  }

  function __destruct()
  {
    $this->close();
  }

  function close()
  {
    if(is_resource($this->_res))
      dba_close($this->_res);
  }
}

