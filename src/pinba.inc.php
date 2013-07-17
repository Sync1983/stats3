<?php

interface IPinba
{
  function startTimer($group, $operation);
  function stopTimer($timer);
}

class gmePinbaNullObject implements IPinba
{
  function startTimer($group, $operation) {}
  function stopTimer($timer) {}
}

class gmePinba implements IPinba
{    
  private $prefix = "stats2_";
  
  function startTimer($group, $operation)
  {
    if($this->_isExtensionExists())     
      return pinba_timer_start(array("group" => $this->prefix.$group, "value" => $this->prefix.$operation));      
  }
  
  function stopTimer($timer)
  {
    if($this->_isExtensionExists() && $timer)
      pinba_timer_stop($timer);
  }
  
  private function _isExtensionExists()
  {
    return extension_loaded('pinba');
  }
}

/**
 * @return IPinba
 */
function gme_pinba()
{
  static $pinba = null;
  if($pinba)
    return $pinba;
  
  $pinba = new gmePinba();  
  return $pinba;
}