<?php

require_once(__DIR__ . '/../stats/DbaWrapper.class.php');
require_once(__DIR__ . '/NarodUploader.class.php');
require_once(__DIR__ . '/CurlBotService.class.php');

class ExternalCdn
{
  private $_uploader_conf;
  private $_var_dir;
  
  private $_db;
  private $_uploader;

  function __construct($var_dir, $uploader_conf)
  {
    $this->_var_dir = $var_dir;
    if(!file_exists($this->_var_dir))
      mkdir($this->_var_dir, 0777, true);
    $this->_uploader_conf = $uploader_conf;
  }

  function getUrl($file, $not_create = true)
  {
    if(!file_exists($file))
      throw new Exception('File not found: '.$file);
    $md5 = md5(file_get_contents($file)); // not replace to md5_file!
    $url = $this->_getDb()->get($md5);
    if($url)
      return $url;
    if($not_create)
      return null;

    $dir_name = 'cdn/'.substr($md5, 0, 2);  
    $file_name = substr($md5, 2, 30) . '_' . basename($file);

    $tmp_dir = tempnam($this->_var_dir, 'ecdn');
    unlink($tmp_dir);
    mkdir($tmp_dir);
    $tmp_file = $tmp_dir . '/' . $file_name;
    copy($file, $tmp_file);

    $this->_getUploader()->mkdir($dir_name);
    $try = 0;
    while(true)
    {
      $try++;
      try
      {
        $url = $this->_getUploader()->upload($dir_name, $tmp_file);
      } catch (Exception $e) {
        if($try > 2)
          throw $e;
        continue;
      }
      break;
    }

    unlink($tmp_file);
    rmdir($tmp_dir);

    $this->_getDb()->set($md5, $url);    
    return $url;
  }

  protected function _getDb()
  {
    if(null === $this->_db)        
      $this->_db = new DbaWrapper($this->_var_dir . '/urls.db', 'inifile');
    return $this->_db;
  }

  protected function _getUploader()
  {
    if(null === $this->_uploader)
    {
      $this->_uploader_conf['var_dir'] = $this->_var_dir;
      $this->_uploader = NarodUploader :: createByConf($this->_uploader_conf);
    }
    return $this->_uploader;
  }
}

