<?php

lmb_require('limb/core/src/lmbBacktrace.class.php');
lmb_require('limb/fs/src/lmbFs.class.php');
lmb_require('src/cron/CronJob.class.php');
lmb_require('src/cron/CronJobFileLogger.class.php');
lmb_require('src/util/ProcessLock.class.php');
lmb_require('limb/core/src/lmbErrorGuard.class.php');

class CronRunner
{
  protected $logger;

  function __construct()  
  {
    new lmbBacktrace(); 
  }

  function run($argv, $argc)
  {
    if($argc < 2)
      die('Usage: cron_runner cron_job_name' . PHP_EOL);

    $debug_mode = in_array('-d', $argv);
    
    $cron_job_name = $argv[1];
    $cron_job_class = $cron_job_name . 'Job';

    $logger = $this->logger = new CronJobFileLogger($cron_job_name);

    lmbErrorGuard :: registerFatalErrorHandler($this, 'fatalError');
    set_error_handler(array($this, 'errorToLog'), E_ALL);
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    set_time_limit(0);
    ini_set('memory_limit', '128M');

    $lock_dir = LIMB_VAR_DIR . 'cron_job_lock/';
    if(!file_exists($lock_dir))
      lmbFs :: mkdir($lock_dir, 0777);

    $lock = new ProcessLock($lock_dir . $cron_job_name, getmypid(), null);
    if(!$lock->lock())
      return $logger->conflict();

    try 
    {
      echo "Run {$cron_job_class} ".date('r')."\n";
      lmb_require('src/cron/' . $cron_job_class . '.class.php');
      $job  = new $cron_job_class;
      
      if(in_array('-ld', $argv))
        $logger->start();

      //ob_start();
      $result = $job->run();
      //$output = ob_get_contents();
      //ob_end_clean();

      $logger->end();
      $lock->unLock();
    }
    catch (lmbException $e)
    {
      echo "Exception: ".$e->getMessage();
      $logger->exception($e);
      $lock->unLock();
    }
  }

  function fatalError($error)
  {
    $this->logger->fatalError($error['message'] . (isset($error['file']) ? ' file ' . $error['file'] : '') . (isset($error['line']) ? ' line ' . $error['line'] : '')); 
  }

  function errorToLog($errno = 0, $errstr = '', $errfile = null, $errline = null, $errcontext = array())
  {
    if(error_reporting() == 0)
      return;
    $back_trace = new lmbBacktrace(1);
    $this->logger->error($errno, $errstr, $errfile, $errline, $errcontext, $back_trace);
  }
}
