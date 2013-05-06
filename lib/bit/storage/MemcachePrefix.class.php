<?php

class MemcachePrefix
{
  function __construct($prefix)
  {
    $this->_prefix = $prefix;
    $this->_prefix_len = strlen($this->_prefix);
    $this->_memcache = null;
  }

  function flush()
  {
    if($this->_memcache)  
      return $this->_memcache->flush();
    return false;
  }

  function connect($host, $port)
  {
    $this->_memcache = new Memcache;
    $this->_memcache->connect($host, $port);
  }
  
  function get($keys)
  {
    if(!is_array($keys))
      return $this->_memcache->get($this->_prefix . $keys);

    if(!count($keys) || null === $this->_memcache)
      return array();

    foreach($keys as $key => $id)
      $keys[$key] = $this->_prefix . $id;

    $values = array();                
    // Вылетает ошибка сегментирования при большем количестве ключей (100000)
    foreach(array_chunk($keys, 1000, true) as $ids_ch)
    {
      $gets = $this->_memcache->get($ids_ch) ?: array();
      foreach($gets as $id => $value)
        $values[substr($id, $this->_prefix_len)] = $value;
    }
    return $values;
  }

  function set($key, $value, $params, $ttl)
  {
    return $this->_memcache->set($this->_prefix . $key, $value, $params, $ttl);    
  }
}
