<?php

function bit_storage($name)
{
  static $storages = array();
  if(!isset($storages[$name]))
  {
    list($class, $args) = bit_conf('storage_' . $name);
    $storages[$name] = new $class($args);
  }
  return $storages[$name];
}

function bit_storage_prepare($name)
{
  list($class, $args) = bit_conf('storage_' . $name);
  call_user_func_array(array($class, 'prepare'), array($args));
}

function bit_memcache($name = 'default')
{
  $conf = bit_conf('memcache_'.$name, true);
  if(!$conf)
    return null;
  return bit_memcache_connection($conf['host'], $conf['port'], isset($conf['prefix']) ? $conf['prefix'] : null);
}

function bit_memcache_connection($host, $port, $prefix = null)
{
  static $connections = array();
  $key = $host . ":" . $port . ':' . $prefix;
  if(!isset($connections[$key]))
  {
    $connection = $prefix ? new MemcachePrefix($prefix) : new Memcache;
    $connection->connect($host, $port);
    $connections[$key] = $connection;
  }
  return $connections[$key];
}

function bit_memcache_set($key, $value, $ttl = 604800)
{
  $cache = bit_memcache();
  if(!$cache)
    return;
  $cache->set($key, bit_serialize(array(crc32($key), $value)), 0, $ttl);
}

function bit_memcache_get($key)
{
  $cache = bit_memcache();
  if(!$cache)
    return null;
  $value = $cache->get($key);
  if(!$value || !is_array($value = @bit_unserialize($value)) || count($value) != 2)
    return null;
  list($skey, $rvalue) = $value;
  if($skey != crc32($key))
    return null;
  return $rvalue;
}

$conf = bit_conf();
$conf->lazy_class_paths['ObjectStorage'] = 'bit/storage/ObjectStorage.interface.php';
$conf->lazy_class_paths['DbStorage'] = 'bit/storage/DbStorage.class.php';
$conf->lazy_class_paths['MongoStorage'] = 'bit/storage/MongoStorage.class.php';
$conf->lazy_class_paths['MemcacheProxyStorage'] = 'bit/storage/MemcacheProxyStorage.class.php';
$conf->lazy_class_paths['RedisStorage'] = 'bit/storage/RedisStorage.class.php';
$conf->lazy_class_paths['RedisCollectionsManager'] = 'bit/storage/RedisCollectionsManager.class.php';
$conf->lazy_class_paths['MemcachePrefix'] = 'bit/storage/MemcachePrefix.class.php';
$conf->lazy_class_paths['MemcacheCollectionsManager'] = 'bit/storage/MemcacheCollectionsManager.class.php';
$conf->lazy_class_paths['IStoreCollectionsManager'] = 'bit/storage/ICollectionsManager.interface.php';
$conf->lazy_class_paths['IStoreCollection'] = 'bit/storage/ICollectionsManager.interface.php';
