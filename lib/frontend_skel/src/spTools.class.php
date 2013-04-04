<?php

class spTools extends lmbAbstractTools
{
  protected $_log;
  protected $_real_ip = null;
  protected $_skip_view_render = null;
  protected $_all_ip_info = null;

  function getJsVars()
  {
    $vars = array();
    $vars['base_path'] = lmb_env_get('LIMB_HTTP_BASE_PATH');
    $vars['offset_path'] = lmb_env_get('LIMB_HTTP_OFFSET_PATH');
    return $vars;
  }

  function skipViewRender()
  {
    if(!is_null($this->_skip_view_render))
      return $this->_skip_view_render;
    return false;
  }

  function setSkipViewRender($value)
  {
    $this->_skip_view_render = $value;
  }

  function isAjaxRequest()
  {
    $request = $this->toolkit->getRequest();
    return $request->getPost('is_ajax') || $request->getGet('is_ajax');
  }

  function getLog()
  {
    if(!$this->_log)
    {
      $this->_log = new SimpleLog();
      if(lmb_env_get('LIMB_VAR_DIR'))
      {
        $uri = new lmbUri;
        $uri->setPath(lmb_env_get('LIMB_VAR_DIR') . '/log/combine.log');
        $this->_log->registerWriter(new SimpleLogOneFileWriter($uri));
      }
    }
    return $this->_log;
  }
  
  function getCronLog()
  {
    $log = new SimpleLog();
    if(lmb_env_get('LIMB_VAR_DIR'))
    {
      $uri = new lmbUri;
      $uri->setPath(lmb_env_get('LIMB_VAR_DIR') . '/log/cron.log');
      $log->registerWriter(new SimpleLogOneFileWriter($uri));
    }
    return $log;
  }
  
  function addVersionToUrl($file_src, $safe = false)
  { 
    list($file_src, $version) = $this->toolkit->getNormalizeUrlAndVersion($file_src, $safe);
    return '/' . lmb_env_get('LIMB_HTTP_OFFSET_PATH') . '_/' . $version . '/' . ltrim($file_src, '/');
  }
  
  function getRealIp()
  {
    if(is_null($this->_real_ip))
      $this->_real_ip = $this->_detectRealIp();
    return $this->_real_ip;
  }

  protected function _detectRealIp()
  {
    foreach($this->getAllIpFields() as $name)
    {
      if(!empty($_SERVER[$name]))
      {
        foreach(array_map('trim', explode(',', $_SERVER[$name])) as $ip)
          if(@ip2long($ip) !== false)
            return $ip;
      }
    }
    return false;
  }

  function getAllIpFields()
  {
    return array(
      'HTTP_X_FORWARDED_FOR',
      'HTTP_X_REAL_IP',
      'HTTP_X_FORWARDED',
      'HTTP_X_CLUSTER_CLIENT_IP',
      'HTTP_CLIENT_IP',
      'HTTP_FORWARDED_FOR',
      'HTTP_FORWARDED',
      'REMOTE_ADDR'   
    );
  }
  
  function getAllIpInfo()
  {
    if(null === $this->_all_ip_info)
    {
      $info = array();
      foreach($this->getAllIpFields() as $name)
        if(!empty($_SERVER[$name]))
          $info[$name] = $_SERVER[$name];
      $this->_all_ip_info = $info;
    }
    return $this->_all_ip_info;
  }
  
  function getEncodeRealIp()
  {
    $ip = $this->getRealIp();
    return $ip ? ip2long($ip) : 0;
  }

  function getDefaultAjaxSlots()
  {
    return array('js_ready', 'content');
  }
}
