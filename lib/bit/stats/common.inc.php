<?php

require_once('bit/game/HashMapFixedSize.class.php');
require_once(__DIR__ . '/DbaWrapper.class.php');
require_once(__DIR__ . '/DbaCounter.class.php');
require_once(__DIR__ . '/DbaIncrement.class.php');
require_once(__DIR__ . '/StatsLogMapping.class.php');
require_once(__DIR__ . '/StatsLogReader.class.php');
require_once(__DIR__ . '/StatsLogParser.class.php');
require_once(__DIR__ . '/StatsMetricVisit.class.php');
require_once(__DIR__ . '/StatsProject.class.php');
require_once(__DIR__ . '/StatsMetricCounters.class.php');
require_once(__DIR__ . '/StatsUniqueRegister.class.php');

require_once(__DIR__ . '/StatsProjectsInfo.interface.php');
require_once(__DIR__ . '/StatsProjectsManager.class.php');
require_once(__DIR__ . '/api/BitStatsApiClient.class.php');

function bc_exec($vars_to_evals, $scale = 0)
{
  $vars = array();
  $lines = array(
    'scale='.intval($scale)
  );
  foreach($vars_to_evals as $var => $value)
  {
    $vars[] = $var;
    $lines[] = 'var=0';
    $lines[] = 'var='.strtr($value, "\0\n", '  ');
    $lines[] = 'var';
  }   

  $process = proc_open('bc -q 2>/dev/null', array(array('pipe', 'r'), array('pipe', 'w')), $pipes);

  if(!is_resource($process)) 
    throw new Exception('Failed procopen');
  fwrite($pipes[0], implode("\n", $lines) . "\n");
  fclose($pipes[0]);


  $result = array();

  $read = array($pipes[1]);
  $write = null;
  $error = null;

  $start = microtime(1);

  $key = 0;
  while(stream_select($read, $write, $error, 0, 500000))
  {
    $line = stream_get_line($pipes[1], 1024, "\n");
    if(!isset($vars[$key]))
      break;
    $result[$vars[$key]] = $line;
    $key++;
    if(!($key % 3) && (microtime(1) - $start) > 1)
      break;
  }
  
  fclose($pipes[1]);
  proc_terminate($process);

  return $result;
}
