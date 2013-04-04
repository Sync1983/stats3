<?php

class DbaShardsManager
{
  private $_connections;
  private $_conf;
  private $_names = array();

  static function instance()
  {
    static $instance = null;
    if(null === $instance)
      $instance = new self;
    return $instance;
  }

  protected function __construct()
  {
    $this->_conf = bit_conf('dba_shards');
  }

  function getAllNames()
  {
    $names = array();
    foreach($this->_conf as $id => $conf)
      $names[] = $this->_getConnectionName($id);
    return $names;
  }

  function getRandShardIdById($user_id)
  {
    list($shard_id) = array_slice(array_keys($this->_conf), $user_id % count($this->_conf), 1);
    return $shard_id;
  }

  function getConnection($id)
  {
    return dbal_by_name($this->_getConnectionName($id));
  }

  private function _getConnectionName($id)
  {
    if(!isset($this->_names[$id]))
    {
      if(!isset($this->_conf[$id]))
        throw new Exception('Shard "'.$id.'" not isset in conf');
      $name = 'shard_'.$id;
      $conf_name = 'db_'.$name;
      $this->_names[$id] = $name;
      bit_conf()->$conf_name = $this->_conf[$id];
    }
    return $this->_names[$id];
  }
}
