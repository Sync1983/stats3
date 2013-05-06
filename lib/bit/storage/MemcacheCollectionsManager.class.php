<?php

class MemcacheCollectionsManager implements IStoreCollectionsManager
{
  function __construct($conf)
  {
    $this->_memcache = bit_memcache_connection($conf['host'], $conf['port'], isset($conf['prefix']) ? $conf['prefix'] : null);
  }

  function create($id)
  {
    return new MemcacheCollection($this->_memcache, $id);  
  }

  function flushIfShemaChange($version)
  {
    if($this->_memcache->get('schema_version') == $version)
      return false;
    if(!$this->_memcache->flush())
      throw new Exception('Failed flush memcache');
    if(!$this->_memcache->set('schema_version', $version, 0, 3600*365))
      throw new Exception('Failed set version');
    return true;
  }

  function flush()
  {
    if(!$this->_memcache->flush())
      throw new Exception('Failed flush memcache');
  }
}

class MemcacheCollection implements IStoreCollection
{
  protected $_conn;
  protected $_uid;
  protected $_version = null;

  function __construct($conn, $uid)  
  {
    $this->_conn = $conn;  
    $this->_uid = "v".$uid;
  }

  function get($id)
  { 
    $value = $this->getAll();
    return ($value && isset($value[$id])) ? $value[$id] : null;
  }

  function getAll()
  {
    return $this->_read(false);
  }

  function rawGetAll()
  {
    return $this->_read(true);
  }

  protected function _read($raw)
  {
    $value = $this->_conn->get($this->_uid);
    if(!$value)
      return null;
    $value = bit_unserialize($value);
    if(!is_array($value) || $value[0] != $this->_uid)
      return null;
    list($uid, $version, $rows) = $value;
    if(!$raw && $this->_version != $version)
      return null;
    return $rows;
  }

  function version($value)
  {
    $this->_version = $value;
  }

  function setAll($rows)
  {
    $this->_conn->set($this->_uid, bit_serialize(array($this->_uid, $this->_version, $rows)), 0, 3600*48);
  }

  function flush()
  {
    $this->_conn->set($this->_uid, '', 0, 5);  
  }
}
