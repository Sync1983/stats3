<?php

require_once('/home/ammonit/dev/projects/stats/share_php/lib/bit/stats/api/BitStatsApiClient.class.php');

list($date, $values) = unserialize(file_get_contents('/home/ammonit/dev/projects/stats/req'));

$api = new BitStatsApiClient('http://192.168.4.33/farmarine/stats/', 'ugqcjdg8qil21k8hh8519g0fih1n5h2u');

$countes = array();
foreach(array_keys($values) as $key)
  $countes[$key] = array('title' => $key, 'is_hidden' => 1);

//$api->setCounters($countes, array('chartone' => array('title' => 'ch', 'counters' => array($key))));
$api->setCountersValues($date, $values);



