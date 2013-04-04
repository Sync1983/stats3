<?php

class ActiveRecord
{
  protected $_is_loaded = false;
  protected $_fields = array();
  protected $_dirty_fields = array();
  protected $_id = null;

  protected $_table_name = null;
  protected $_db_fields = null;

  function __construct($id = null)
  {
    $this->_id = null === $id ? null : intval($id);
    if($this->_table_name === null || $this->_db_fields === null) // TODO abstract functions?
      throw new Exception('Required define table_name and db_fields');
  }

  function export()
  {
    $this->_loadFields();
    return $this->_fields;
  }

  function isDirty()
  {
    return count($this->_dirty_fields);
  }

  function isNew()
  {
    return null === $this->_id;
  }

  function getId()
  {
    return $this->_id;
  }

  function get($name)
  {
    if(!$this->_is_loaded && !isset($this->_fields[$name]))
      $this->_loadFields();
    return isset($this->_fields[$name]) ? $this->_fields[$name] : null;
  }

  function isLoaded()
  {
    return $this->_is_loaded;
  }

  function tryLoad()
  {
    return $this->_loadFields(true);    
  }

  function save()
  {
    if(!$this->_dirty_fields)
      return false;
    $conn = $this->_dbal();
    $data = array();
    foreach(array_intersect($this->_db_fields, array_keys($this->_dirty_fields)) as $field)
      $data[$field] = $conn->escape($this->_fields[$field]);

    if(!count($data))
      return false;

    if(null !== $this->_id)
      $data['id'] = $conn->escape($this->_id);

    $this->_insertQuery($data, $conn, null === $this->_id);
    if(null === $this->_id)
      $this->_id = $conn->getInsertId();

    $this->_dirty_fields = array();
    return true;
  }

  protected function _insertQuery($data, $conn, $insert_only)
  {
    $update = array();
    foreach($data as $field => $value)
      $update[] = $field.'=VALUES('.$field.')';
    $sql = 'INSERT INTO '.$this->_table_name.' ('.implode(',', array_keys($data)).') VALUES (\''.implode('\',\'', $data).'\') ';
    if(!$insert_only)
      $sql .= 'ON DUPLICATE KEY UPDATE '.implode(',', $update);
    $conn->execute($sql);
  }

  function set($name, $value)
  {
    $this->_fields[$name] = $value;
    $this->_dirty_fields[$name] = true;
  }

  protected function _loadFields($safe = false)
  {
    if($this->_is_loaded)
      return true;
    if(null === $this->_id)
      return;
    $rows = $this->_dbal()->fetch('SELECT * FROM '.$this->_table_name.' WHERE id='.intval($this->_id));
    if(!$rows)
    {
      if($safe)
        return;
      throw new Exception('Failed load '.get_class($this).' for id: '.intval($this->_id));
    }
    foreach($rows[0] as $field => $value)
      if(!isset($this->_dirty_fields[$field]))
        $this->_fields[$field] = $value;    
    $this->_is_loaded = true;
    return true;
  }

  protected function _forceSetFields($data)
  {
    $this->_is_loaded = true;
    $this->_dirty_fields = array();
    $this->_fields = $data;
  }

  protected function _dbal()
  {
    return dbal();
  }

  static function findBySql($class, $sql)
  {
    $items = array();
    foreach(dbal()->fetch($sql) as $data)
    {
      $item = new $class($data['id']);
      unset($data['id']);
      $item->_forceSetFields($data);
      $items[] = $item;
    }
    return $items;
  }
}
