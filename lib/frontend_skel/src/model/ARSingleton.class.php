<?php

// One class - one record (kind primary key)
class ARSingleton extends lmbActiveRecord
{
  protected static $objects = array();
  protected static $_inheritance_field = 'kind';

  protected $_db_table_name = 'ar_singleton';

  protected $_conf_field_data = 'data';
  protected $_conf_fields = array();

  function initialized()
  {
  }

  protected function _setConfValues()
  {
    foreach($this->_conf_fields as $field => $default_value)
      $this->_setRaw($field, $default_value);
  }

  protected function _defineRelations()
  {
    $this->_setConfValues();
    return parent :: _defineRelations(); 
  }

  static function requireAll()
  {
    static $required;
    if(!$required)
    {
      lmb_require('src/model/ar_singleton/*.class.php');
      $required = true;
    }
  }
  
  static function getValue($class_name, $field)
  {
    return self :: findOrCreate($class_name)->get($field);
  }

  static function findOrCreate($class_name)
  {
    self :: requireAll();
    $object = lmbActiveRecord :: findOne($class_name);
    if(!$object)
    {
      $object = new $class_name;
      $object->initialized();
    }
    self :: $objects[$class_name] = $object;
    return $object;
  }

  protected function _onAfterSave()
  {
    self :: $objects[$this->get('kind')] = $this;
  }

  protected function _onBeforeSave()
  {
    $data = array();
    foreach($this->_conf_fields as $field => $default_value)
    {
      $value = $this->_getFieldForSave($field); 
      $data[$field] = $value;
    }
    $this->set($this->_conf_field_data, serialize($data));
    return parent :: _onBeforeSave();
  }

  protected function _getFieldForSave($field)
  {
    return parent :: _hasProperty($field) ? parent :: _getRaw($field) : null;
  }

  function loadFromRecord($record)
  {
    $result = parent :: loadFromRecord($record);

    $data = @unserialize($this->get($this->_conf_field_data));
    if(is_array($data))
    {
      foreach($data as $field => $value)
      {
        if(!array_key_exists($field, $this->_conf_fields))
          continue;
        $this->set($field, $value);
      }
    }
    $this->set($this->_conf_field_data, null);
    return $result;
  }

  function getInt($field)
  {
    return intval($this->get($field));
  }
}
