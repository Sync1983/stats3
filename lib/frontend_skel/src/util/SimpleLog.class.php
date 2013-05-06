<?php

lmb_require('src/util/SimpleLogEntry.class.php');
lmb_require('limb/log/src/lmbLog.class.php');

class SimpleLog extends lmbLog
{
  protected function _write($level, $string, $params = array(), $backtrace = null)
  {
    if(!$this->_isAllowedLevel($level))
      return;

    $entry = new SimpleLogEntry($level, $string, $params, $backtrace);
    $this->logs[] = $entry;

    $this->_writeLogEntry($entry);
  }

  static function formatError($errno = 0, $errstr = '', $errfile = null, $errline = null, $errcontext = array())
  {
    $message = '';
    if($errstr)
      $message .= $errstr;
    if($errno)
      $message .= ', error no: '.$errno;
    if($errfile)
      $message .= ', file '.$errfile;
    if($errline)
      $message .= ' line '.$errline;
    return $message;
  }
  
  function notice($message, $params = array(), $backtrace = null)
  {
    $this->log($message, LOG_NOTICE, $params, $backtrace);
  }
  
  function warning($message, $params = array(), $backtrace = null)
  {
    $this->log($message, LOG_WARNING, $params, $backtrace);
  }
  
  function error($message, $params = array(), $backtrace = null)
  {
    $this->log($message, LOG_ERR, $params, $backtrace);
  }
  
  function info($message, $params = array(), $backtrace = null)
  {
    $this->log($message, LOG_INFO, $params, $backtrace);
  }
  
  function exception($e)
  {
    $this->logException($e);
  }
}
