<?php

class MongoStorage implements ObjectStorage
{
  protected $_values;
  protected $_locks;
  protected $_conn;

  function __construct($conf)
  {
    $this->_conn = isset($conf['dsn']) ? new Mongo($conf['dsn']) : new Mongo;
    $this->_values = $this->_conn->selectCollection($conf['db'], $conf['collection_values']);
    $this->_locks = $this->_conn->selectCollection($conf['db'], $conf['collection_locks']);
  }

  protected function _key($key)
  {
    if(!is_int($key) && ((int) $key) != $key)
      throw new Exception('Not support not integer id - "'.$key.'"');
    return new MongoId(str_pad(base_convert($key, 10, 16), 24, 0, STR_PAD_LEFT));
  }

  function get($key)
  {
    $row = $this->_values->findOne(array('_id' => $this->_key($key)), array('value'));
    if($row)
      return bit_unserialize($row['value']->bin);
  }

  function set($key, $value, $is_dirty = true)  
  {
    $this->_values->save(
      array(
        '_id' => $this->_key($key), 
        'value' => new MongoBinData(bit_serialize($value)), 
        'utime' => time()
      ), 
      array(
        'fsync' => true
      )
    );
  }

  function remove($key)
  {
    $this->_values->remove(array('_id' => $this->_key($key)));
  }

  function fetchOldRecords($utime, $limit = 10)
  {
    $records = array();
    $rows = iterator_to_array($this->_values->find(array('utime' => array('$lte' => (time() - $utime))), array('value'))->limit(intval($limit)));
    foreach($rows as $key => $row)
      $records[base_convert($key, 16, 10)] = bit_unserialize($row['value']->bin);
    return $records;
  }
  
  function flush()
  {
    $this->_values->drop();
    $this->_values->ensureIndex(array('utime' => -1));

    $this->_locks->drop();
    $this->_locks->ensureIndex(array('utime' => -1));
  }

  static function prepare($conf)
  {
    $conn = isset($conf['dsn']) ? new Mongo($conf['dsn']) : new Mongo;
    $db = $conn->selectDB($conf['db']);

    $values = $db->createCollection($conf['collection_values']);
    $values->drop();
    $values->ensureIndex(array('utime' => -1));
    
    $locks = $db->createCollection($conf['collection_locks']);
    $locks->drop();
    $locks->ensureIndex(array('utime' => -1));
  }
}
