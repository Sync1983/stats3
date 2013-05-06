<?php

class Executing
{
  protected $cmd;
  protected $listener;

  protected $stderr;
  protected $stdout;
  
  protected $interrupt_handler;

  function __construct(&$listener, $interrupt_handler = false)
  {
    $this->listener = $listener;
    $this->interrupt_handler = $interrupt_handler;
  }

  function checkConnection()
  {
    echo " ";
    flush();
    return connection_aborted() != 1;
  }

  function interruptHandler()
  {
    if(!$this->interrupt_handler)
      return true;
    return call_user_func_array($this->interrupt_handler, array());
  }

  function exec($cmd)
  { 
    if($this->interrupt_handler === true)
    {
      ignore_user_abort(false);
      $this->interrupt_handler = array($this, 'checkConnection'); 
    }

    set_time_limit(0);
    if(!$this->interruptHandler())
      return;
    $this->listener->message($cmd, '');
    $this->handle = proc_open($cmd . " 2>&1", array(1 => array("pipe", "w")), $pipes);
    
    $this->stdout = $pipes[1];
    stream_set_blocking($this->stdout, 0);

    $this->_readOutput($cmd);

    return proc_close($this->handle);
  }

  function _readOutput($cmd)
  {
    while(true)
    {
      if(!$this->interruptHandler())
      {
        $this->listener->message(' ====================== interrupt handler =========================');
        return;
      }

      $r_start = time();
      while($this->_isUpdate($this->stdout))
      { 
        if(false === ($t_log = fgets($this->stdout)))
          return;

        #if(lmbSys :: isWin32())
        #  $t_log = iconv('cp866', 'utf-8', $t_log);

        $this->listener->message($t_log);

        if((time() - $r_start) > 10)
          continue;
      };
      sleep(1); 
    }
  }

  protected function _isUpdate($resource)
  {
    $read = array($resource);
    return stream_select($read, $write = null, $except = null, 0, 100);
  }

  static function setParamsToCmd($cmd, $params)
  {
    return
      str_replace(
        array_keys($params),
        array_map('escapeshellarg', array_values($params)),
        $cmd 
      );
  }

  function getListener()
  {
    return $this->listener;  
  }
}

