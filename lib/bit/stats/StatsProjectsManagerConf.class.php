<?php

class StatsProjectsManagerConf implements StatsProjectsManager
{
  protected $_conf;

  function __construct($conf)
  {
    $this->_conf = $conf;  
  }

  function getProjectDir($uid)
  {
    if(!$this->hasProject($uid))  
      return false;
    $dir = $this->getBaseDir() . '/' . strtr($uid, array('/' => '-', '.' => '_'));
    if(!file_exists($dir))
      mkdir($dir, 0777, true);
    return $dir; 
  }

  function getProjectLog($uid)
  {
    if(!$dir = $this->getProjectDir($uid))
      return false;
    $dir .= '/logs/';
    if(!file_exists($dir))
      mkdir($dir, 0777, true);
    return $dir . '/current.log';
  }

  function hasProject($uid)  
  {
    return array_key_exists($uid, $this->_getConf('projects'));
  }

  function getBaseDir()
  {
    return $this->_getConf('base_dir');
  }

  protected function _getConf($param)
  {
    if(!isset($this->_conf[$param]))  
      throw new Exception("ProjectsManager: Param '".$param."' not set!");
  }
}

