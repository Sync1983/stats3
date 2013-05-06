<?php

class DbaCounter 
{
  protected $_db;

  function __construct(DbaWrapper $db)
  {
    $this->_db = $db;
  }

  function increment($key)
  {
    $new = 1 + $this->_db->get($key);
    $this->_db->set($key, $new);
    return $new;
  }

  function getDb()
  {
    return $this->_db;
  }
}

