<?php

class RedisStorage implements ObjectStorage
{
  protected $_redis;

  function __construct($conf)
  {
    $redis = new Redis;
    $host = isset($conf['host']) ? $conf['host'] : '127.0.0.1';
    $port = isset($conf['port']) ? $conf['port'] : 6379;
    if(!isset($conf['db']) || !is_numeric($conf['db']))
      throw new Exception('Not define number db in conf!');
    $redis->connect($host, $port);
    if(!$redis->select($conf['db']))
      throw new Exception('Failed select db '.$conf['db']);

    $this->_redis = $redis;
  }

  function get($key)
  {
    $value = $this->_redis->get("".$key);
    if($value)
      return bit_unserialize($value);
  }

  function set($key, $value, $is_dirty = true)  
  {
    if(!is_int($key) && ((int) $key) != $key)
      throw new Exception('Not support not integer id - "'.$key.'"');
    $key = ''.$key;

    if($is_dirty)
      $success = $this->_redis->set($key, bit_serialize($value));
    else
      $success = $this->_redis->setex($key, 172800, bit_serialize($value));
      
    if(!$success)
      throw new Exception('Failed save value to redis');

    if($is_dirty)
      $this->_redis->zAdd('u', time(), $key);
    else
      $this->_redis->zDelete('u', $key);
  }

  function remove($key)
  {
    $key = ''.$key;
    $this->_redis->delete($key);
    $this->_redis->zDelete('u', $key);
  }

  function tryLock($key, $ttl)
  {
    return true;
  }

  function isLocked($key)
  {
    return false;
  }

  function unlock($key)
  {
    
  }

  function fetchOldRecords($utime, $limit = 10)
  {
    $data = array();
    $keys = $this->_redis->zRange('u', 0, $limit - 1, true);
    $time = time() - $utime;
    foreach($keys as $key => $utime)
      if($utime <= $time)
        $data[$key] = $this->get($key);
    return $data;
  }

  function flush()
  {
    if(!$this->_redis->flushDB())
      throw new Exception('Failed flush redis');
  }

  static function prepare($conf)
  {
    $storage = new self($conf);
    $storage->flush();
  }
}
