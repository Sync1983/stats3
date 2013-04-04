<?php

class LazyLogWriter
{
  private $_file;
  private $_lines = array();
  private $_count_skip = 0;

  function __construct($log_file)  
  {
    if(!file_exists(dirname($log_file)))
      if(!mkdir(dirname($log_file), 0777, true))
        throw new Exception('Failed create log dir "'.dirname($log_file).'"');
    $this->_file = $log_file;
  }

  function write($message)
  {
    $this->_lines[] = date('d/M/Y:H:i:s') . ' ' . $message;
    if(100 > $this->_count_skip++)
      $this->_flush();
  }

  function __destruct()
  {
    $this->_flush();
  }

  private function _flush()
  {
    if($this->_lines)
      file_put_contents($this->_file, implode("\n", $this->_lines)."\n", FILE_APPEND);
    $this->_count_skip = 0;
    $this->_lines = array();
  }
}
