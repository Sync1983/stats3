<?php

class RedisCollectionsManager implements IStoreCollectionsManager
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

  function create($id)
  {
    return new RedisCollection($this->_redis, $id);  
  }

  function flushIfShemaChange($version)
  {
    if($this->_redis->get('schema_version') == $version)
      return false;
    $this->_redis->flushDB();
    $this->_redis->set('schema_version', $version);
    return true;
  }

  function flush()
  {
    if(!$this->_redis->flushDB())
      throw new Exception('Failed flush redis');
  }
}

class RedisCollection implements IStoreCollection
{
  protected $_redis;
  protected $_uid;
  protected $_version = null;

  function __construct(Redis $redis, $uid)  
  {
    $this->_redis = $redis;  
    $this->_uid = "".$uid;
  }

  function get($id)
  { 
    $value = $this->getAll();
    return ($value && isset($value[$id])) ? $value[$id] : null;
  }

  function getAll()
  {
    if(!$this->_is())
      return null;
    return $this->rawGetAll();
  }

  function rawGetAll()
  {
    $value = $this->_redis->hGet($this->_uid, 'all');
    if(false === $value)
      return null;
    return bit_unserialize($value);
  }

  function version($value)
  {
    $this->_version = $value;
  }

  function setAll($rows)
  {
    $this->flush();
    $this->_redis->hSet($this->_uid, 'all', bit_serialize($rows));
    $this->_redis->hSet($this->_uid, 'd', $this->_version);
  }

  function flush()
  {
    $this->_redis->delete($this->_uid);  
    $this->_destroy();
  }

  private function _is()
  {
    return null !== $this->_version && $this->_redis->hGet($this->_uid, 'd') == $this->_version;
  }

  private function _destroy()
  {
    $this->_redis->hDel($this->_uid, 'd');
  }
}
