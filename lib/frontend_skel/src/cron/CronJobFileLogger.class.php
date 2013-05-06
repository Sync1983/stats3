<?php

lmb_require('src/util/SimpleLog.class.php');

class CronJobFileLogger 
{
  protected $pid;
  protected $cron_job_name;
  protected $log;
  protected $error_log;
  protected $start_time;

  function __construct($cron_job_name)
  {
    $this->cron_job_name = $cron_job_name;
    $this->pid = getmypid();
    $this->log = lmbToolkit :: instance()->getCronLog();
    $this->error_log = lmbToolkit :: instance()->getLog();
    $this->start_time =  microtime(1);
  }

  function start()
  {
    $this->log->info($this->_wrap('Start'), array(), new lmbBacktrace(0));
  }
  
  function conflict()
  {
    $this->log->warning($this->_wrap('Conflict'), array(), new lmbBacktrace(0));
  }

  function end()
  {
    $this->log->info($this->_wrap('End', true), array(), new lmbBacktrace(0));    
  }
  
  function exception($e)
  {
    $this->error_log->error($this->_wrap($e->getMessage()), array(), new lmbBacktrace($e->getTrace(), 3));
    //$this->log->error($this->_wrap($e->getMessage(), true), array(), new lmbBacktrace($e->getTrace(), 10));
  }

  function error($errno, $errstr, $errfile, $errline, $errcontext, $back_trace)
  {
    $message = $this->_wrap(SimpleLog :: formatError($errno, $errstr, $errfile, $errline, $errcontext));
    $this->error_log->error($message);
    //$this->log->error($message, array(), $back_trace);
  }

  function fatalError($message)
  {
    $this->error_log->error($this->_wrap($message, true), array(), new lmbBacktrace(0));
    //$this->log->error($this->_wrap($message, true), array(), new lmbBacktrace(0));
  }

  protected function _wrap($message, $add_run_time = false)
  {
    return 
      '[pid:'.$this->pid.'][cron:'.$this->cron_job_name.']' 
      . ($add_run_time ? sprintf('[run time: %.4f][%.2fMb]', microtime(1) - $this->start_time, memory_get_peak_usage()/1048576) : '')
      . ' ' .  $message;
  }
}
