<?php

abstract class CronJob
{
  const LOG_TABLE = 'cron_job_log';

  abstract function run();
}
