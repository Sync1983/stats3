<?php

require_once('bit/core.inc.php');
require_once('bit/stats/common.inc.php');

bit_conf()->varDir = '/tmp/';

function test_stats($method = null)
{
  static $test_helper = null;  
  if(null === $test_helper)
    $test_helper = new StatsTestHelper;
  if($method)
    return call_user_func_array(array($test_helper, $method), array_slice(func_get_args(), 1));
  return $test_helper;
}

class StatsTestHelper  
{
  function varDir()
  {
    return __DIR__ . '/../var/';
  }

  function writeLog($file, $date, $query = array())
  {
    file_put_contents($file, date('d/M/Y:H:i:s O', $date) . ',' . http_build_query($query) . "\n", FILE_APPEND);
  }
}
