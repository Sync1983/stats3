<?php

require_once(__DIR__ . '/../../setup.php');

function st_write_log($file, $date, $query = array())
{
  file_put_contents($file, date('d/M/Y:H:i:s O', $date) . ',' . http_build_query($query) . "\n", FILE_APPEND);
}

$manager = lmbToolkit :: instance()->getStatsManager();
$stproject = $manager->getProject(2);

$metric = $stproject->getStatsMetricCounters();

$time = time();
for($day = $time - 86400*2; $day < $time; $day += 86400)
{
  $metric->onIncrement($day, array(
    'b.1' => 2,
    'b.2' => 7,
    'c.2' => 100
  ));  
}
$metric->flush();

