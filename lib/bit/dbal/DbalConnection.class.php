<?php

class DbalConnection
{
  protected $_conn;
  protected $_conf;
  protected $_fetch_all_exists = false;

  function __construct($conf)
  {
    $conf['port'] = isset($conf['port']) ? $conf['port'] : null; 
    $this->_conf = $conf;
    $this->_conn = null;
    $this->_fetch_all_exists = function_exists('mysqli_fetch_all');
  }

  function getConf()
  {
    return $this->_conf;
  }

  function tryConn()
  {
    return !!$this->_connection(true);
  }

  private function _connection($safe = false)
  {
    if(null === $this->_conn)  
    {
      $conf = $this->_conf;

      if($safe)
      {
        $conn = mysqli_init();
        $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1);
        @$conn->connect($conf['host'], $conf['user'], $conf['password'], $conf['database'], $conf['port']);
        if(!(@$conn->ping()))
          return false;
        $this->_conn = $conn;
      }
      else
        $this->_conn = mysqli_connect($conf['host'], $conf['user'], $conf['password'], $conf['database'], $conf['port']);

      if(false === $this->_conn)
      {
        if($safe)
          return false;
        throw new Exception('Failed connect to mysql server.');
      }
      $skip_charset = isset($this->_conf['skip_charser']) ? $this->_conf['skip_charser'] : false;
      if(!$skip_charset && !mysqli_query($this->_conn, "SET NAMES 'utf8'"))
        throw new Exception("MYSQL: " . mysqli_error($this->_conn) . " (SQL: SET NAMES 'utf8')");
    }
    return $this->_conn;
  }

  function safeExecute($sql)
  {
    try
    {
      $this->execute($sql);
      return true;
    } catch (Exception $e) {
      bit_error_guard($e);
    };
    return false;
  }

  function execute($sql)
  {
    if(!$result = mysqli_query($this->_connection(), $sql))
      throw new Exception("MYSQL: " . mysqli_error($this->_connection()) . " (SQL: " . $sql . ")");
    //bit_log_write(substr($sql, 0, 100), 'sql');
    return $result;
  }

  function fetch($sql)
  {
    $query_id = $this->execute($sql);
    $fields = $query_id->fetch_fields();
    $to_int = array();
    $to_check_int = array();
    foreach($fields as $key => $info)
    {
      switch($info->type)
      {
        case MYSQLI_TYPE_BIT:
        case MYSQLI_TYPE_TINY:
        case MYSQLI_TYPE_INT24:
        case MYSQLI_TYPE_SHORT:
          $to_int[] = $info->name;
          break;
        case MYSQLI_TYPE_LONG:
        case MYSQLI_TYPE_LONGLONG:
          $to_check_int[] = $info->name;
          break;
      }
    }
    $rows = array(); 
    while($row = $query_id->fetch_assoc())
    {
      foreach($to_int as $field)
        $row[$field] = (int) $row[$field];
      foreach($to_check_int as $field)
        if($row[$field] > 0 ? PHP_INT_MAX > $row[$field] : $row[$field] > ~PHP_INT_MAX)
          $row[$field] = (int) $row[$field];
      $rows[] = $row;
    }
    $query_id->free();
    return $rows;
  }

  function fetchOneValue($sql)
  {
    $query_id = $this->execute($sql);
    $value = $query_id->fetch_array();
    $query_id->free();
    return $value ? $value[0] : $value;
  }

  function query($sql)
  {
    return $this->execute($sql);
  }

  function getInsertId()
  {
    return mysqli_insert_id($this->_connection());
  }

  function escape($string)
  {
    return mysqli_escape_string($this->_connection(), $string);
  }
}

