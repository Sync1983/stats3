<?php

class StatsProjectsManager
{
  protected $_has_cache;
  protected $_log_cache;
  protected $_dir_cache;
  protected $_info;
  protected $_base_dir;

  public $is_ready_only = true;

  function __construct(StatsProjectsInfo $info)
  {
    $this->_has_cache = new HashMapFixedSize(1000);  
    $this->_log_cache = new HashMapFixedSize(1000);  
    $this->_dir_cache = new HashMapFixedSize(1000);  
    $this->_info = $info;
    $this->_base_dir = $info->getBaseDir();
    $this->_mkdir($this->_base_dir);
  }

  function getInfo()
  {
    return $this->_info;
  }

  function getProjectDir($uid)
  {
    if($dir = $this->_dir_cache->get($uid))
      return $this->_base_dir . '/' . $dir;
    if(!$this->hasProject($uid))
      return false;
    $dir = $this->_info->getProjectDir($uid);
    $this->_dir_cache->set($uid, $dir);
    if(!$dir)
      return false;
    $dir = $this->_info->getBaseDir() . '/' . $dir;
    $this->_mkdir($dir);
    return $dir;
  }

  function getProjectLog($uid)
  {
    if($file = $this->_log_cache->get($uid))
      return $file;
    if(!$dir = $this->getProjectDir($uid))
      return false;
    $file = $dir . '/logs/' . date('Ymd') . '.log';
    $this->_mkdir(dirname($file));
    $this->_log_cache->set($uid, $file);
    return $file;
  }

  function hasProject($uid)
  {
    if(null !== ($value = $this->_has_cache->get($uid)))
      return $value;
    $value = (bool) $this->_info->hasProject($uid);
    $this->_has_cache->set($uid, $value);
    return $value;
  }

  function getBaseDir()
  {
    return $this->_base_dir;
  }

  function getLogsMapper()
  {
    return new StatsLogMapping($this);
  }

  function getNextProjectUid($current_uid)
  {
    return $this->_info->getNextProjectUid($current_uid);
  }

  function getLogParser($uid)
  {
    if(!$project = $this->getProject($uid))
      return false;
    return new StatsLogParser($project);
  }

  function getProject($uid)
  {
    if(!$dir = $this->getProjectDir($uid))
      return false;
    return new StatsProject($uid, $dir, $this->_info, $this->is_ready_only);
  }

  protected function _mkdir($dir)
  {
    if(!is_dir($dir))
      if(!mkdir($dir, 0777, true))
        throw new Exception('Failed create dir "'.$dir.'"');
  }
}
