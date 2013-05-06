<?php

class MemcacheProxyStorage implements ObjectStorage
{
  protected $_memcache;
  protected $_store_ttl = 3600; 

  function __construct($conf)
  {
    $this->_memcache = bit_memcache_connection($conf['host'], $conf['port']);
    $class = $conf['db_class'];
    $this->_db = new $class($conf['db_conf']);
    $this->_prefix = isset($conf['prefix']) ? $conf['prefix'] : '';
  }

  protected function _key($key, $preffix = 'v')
  {
    return 'bstorage-' . $this->_prefix . $preffix . $key;  
  }

  function get($key)
  {
    $value = $this->_memcache->get($this->_key($key));
    if(!is_array($value) || count($value) != 2 || $value[0] != $key)
      $value = $this->_db->get($key);
    else
      $value = $value[1];
    if($value)
      return bit_unserialize($value);
  }

  function set($key, $value, $is_dirty = true)  
  {
    $value = bit_serialize($value);
    if(!$this->_memcache->set($this->_key($key), array($key, $value), false, $this->_store_ttl))
      $this->_memcache->delete($this->_key($key));
    $this->_db->set($key, $value, $is_dirty);
  }

  function remove($key)
  {
    $this->_memcache->delete($this->_key($key));
    $this->_db->remove($key);
  }

  function fetchOldRecords($utime, $limit = 10)
  {
    $rows = $this->_db->fetchOldRecords($utime, $limit);
    foreach($rows as $key => $row)
      $rows[$key] = bit_unserialize($row);
    return $rows;
  }
  
  function flush()
  {
    $this->_memcache->flush();
    $time = time();
    $this->_db->flush();
    sleep(max(2, min(0, time() - $time)));
  }

  static function prepare($conf)
  {
    $memcache = new Memcache;
    $memcache->connect($conf['host'], $conf['port']);
    $memcache->flush();
    $time = time();

    call_user_func_array(array($conf['db_class'], 'prepare'), array($conf['db_conf']));
    sleep(max(2, min(0, time() - $time)));
  }
}
