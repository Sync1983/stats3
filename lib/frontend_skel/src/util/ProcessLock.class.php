<?php

lmb_require('limb/core/src/lmbObject.class.php');

class ProcessLock
{
  protected $lock_file;
  protected $pid;
  protected $limit_lock_time;
  protected $last_write_content = null;
  
  function __construct($lock_file, $pid, $limit_lock_time = null)
  {
    $this->lock_file = $lock_file;
    $this->pid = $pid;
    $this->limit_lock_time = $limit_lock_time;
  }

  function lock()
  {
    return $this->_processLockFile();
  }

  function unLock()
  {
    @unlink($this->lock_file);
  }

  function safeUnlock()
  {
    if(!is_null($this->last_write_content) && $this->last_write_content === @file_get_contents($this->lock_file, false))
    {
      $this->unLock();
      return true;
    }
    return false;
  }

  protected function _processLockFile()
  {
    $lock_file = $this->lock_file;

    if($this->isLock())
      return false;
      
    $file_lock_valid_content = serialize(array($this->pid, $this->_getArgsProcess($this->pid), time()));
    $this->last_write_content = $file_lock_valid_content;

    file_put_contents($lock_file, $file_lock_valid_content);
    clearstatcache();
    return ($file_lock_valid_content === @file_get_contents($lock_file, false));
  }

  function isLock()
  {
    if(!file_exists($this->lock_file))
      return false;
    $lock_content = @file_get_contents($this->lock_file, false);
    if($lock_content === false)
      return false;
    $lock_content = @unserialize($lock_content);
    
    if(!is_array($lock_content))
      return false;

    if(!$this->_issetProcess($lock_content))
      return false;
    if($this->limit_lock_time && (time() >= ($lock_content[2] + $this->limit_lock_time)))
      lmbToolkit :: instance()->getLog()->warning(
        'Limit exceeded expectations unlock, file: ' . $this->lock_file . 
        ', limit: ' . $this->limit_lock_time . 
        ', elapsed:' . (time() - $lock_content[2]));
    return true;
  }

  protected function _issetProcess($lock_content)
  {
    return
      !($this->pid == $lock_content[0] && $this->_getArgsProcess($this->pid) == $lock_content[1]) /// not my
      && ($pid = escapeshellarg($lock_content[0]))
      && (`ps -p $pid -o pid=` == $lock_content[0] /// process isset
        && $this->_getArgsProcess($lock_content[0]) == $lock_content[1]); /// args process equal
  }

  protected function _getArgsProcess($pid)
  {
    $pid = escapeshellarg($pid);
    return `ps -p $pid -o args=`;
  }

  function getLockFile()
  {
    return $this->lock_file;
  }
}
