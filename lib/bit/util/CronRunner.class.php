<?php

bit_lazy_class('ProcessLock', 'bit/util/ProcessLock.class.php');

class CronRunner
{
  function run($argv, $argc)
  {
    if($argc < 2)
    {
      trigger_error('Usage: cron_runner cron_job_name');
      return;
    }
    $cron_job_class = $argv[1];

    error_reporting(E_ALL);
    ini_set('display_errors', true);
    set_time_limit(0);
    $lock_dir = bit_conf('var_dir') . '/cron/lock/';
    if(!file_exists($lock_dir))
      mkdir($lock_dir, 0777, true);

    $job_dir = bit_conf('cron_jobs_dir');
    $job_file = $job_dir . '/' . $cron_job_class . '.class.php';
    if(!file_exists($job_file))
    {
      trigger_error('Cron job "'.$cron_job_class.'" not found by path "'.$job_file.'"');
      return;
    }
    bit_lazy_class($cron_job_class, $job_file);
    
    $lock = new ProcessLock($lock_dir . $cron_job_class, getmypid(), null);
    if(!$lock->lock())
    {
      trigger_error('Cron job "'.$cron_job_class.'" conflict!');
      return;
    }

    $job = new $cron_job_class;
    $job->run();
    $lock->safeUnlock();
  }
}
