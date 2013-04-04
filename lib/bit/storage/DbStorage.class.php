<?php

class DbStorage implements ObjectStorage
{
  protected $_db;

  function __construct($table_name)
  {
    $this->_db = dbal();
    $this->_table_name = '`' . strtr($this->_db->escape($table_name), array('`' => '')) . '`'; 
  }

  protected function _key($key, $preffix = 'v')
  {
    return $preffix . $key;  
  }

  function get($key)
  {
    $key = $this->_key($key);
    $value = $this->_db->fetchOneValue('SELECT value FROM '.$this->_table_name.' WHERE `key`=\''.$this->_db->escape($key).'\'');  
    if($value)
      return bit_unserialize($value);
  }

  function set($key, $value, $is_dirty = true)  
  {
    $key = $this->_key($key);
    $value = bit_serialize($value);
    $this->_db->execute('INSERT INTO '.$this->_table_name.' (`key`, `value`, `utime`) VALUES '.
      ' (\''.$this->_db->escape($key).'\', \''.$this->_db->escape($value).'\', '.time().')'.
      ' ON DUPLICATE KEY UPDATE value=VALUES(value), utime=VALUES(utime)');
  }

  function remove($key)
  {
    $key = $this->_db->escape($this->_key($key, 'v'));
    $this->_db->execute('DELETE FROM '.$this->_table_name.' WHERE `key`=\''.$key.'\'');
  }

  function fetchOldRecords($utime, $limit = 10)
  {
    $rows = dbal()->fetch('SELECT `key`, value FROM '.$this->_table_name.' WHERE utime <= '.(time() - $utime).' AND `key` LIKE \'v%\' LIMIT '.intval($limit));  
    $data = array();
    foreach($rows as $row)
      $data[substr($row['key'], 1)] = bit_unserialize($row['value']);
    return $data;
  }

  function flush()
  {
    $this->_db->execute('TRUNCATE '.$this->_table_name);
  }

  static function prepare($conf)
  {
    $table_name = '`' . strtr(dbal()->escape($conf), array('`' => '')) . '`';

    dbal()->execute('drop table if exists '.$table_name);
    dbal()->execute(
"CREATE TABLE  ".$table_name." (
  `key` char(33) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `value` longblob NOT NULL,
  `utime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`key`),
  KEY `utime` (`utime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
  }
}
