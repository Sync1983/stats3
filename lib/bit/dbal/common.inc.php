<?php

function dbal()
{
  static $connection = null;  
  if(null === $connection)
    $connection = new DbalConnection(bit_conf('db_default'));
  return $connection;
}

function dbal_by_name($name)
{
  static $connections = array();
  if(!isset($connections[$name]))
    $connections[$name] = new DbalConnection(bit_conf('db_'.$name));
  return $connections[$name];
}

function dba_shards()
{
  static $instance = null;
  if(null === $instance)
    $instance = DbaShardsManager :: instance();
  return $instance;
}

$conf = bit_conf();
$conf->lazy_class_paths['DbalConnection'] = 'bit/dbal/DbalConnection.class.php';
$conf->lazy_class_paths['ActiveRecord'] = 'bit/dbal/ActiveRecord.class.php';
$conf->lazy_class_paths['DbaShardsManager'] = 'bit/dbal/DbaShardsManager.class.php';

