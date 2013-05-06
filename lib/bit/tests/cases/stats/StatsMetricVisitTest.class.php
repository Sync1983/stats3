<?php

class StatsMetricVisitTest extends UnitTestCase
{
  function testReturn()
  {
    $helper = test_stats();
    $project_dir = tempnam($helper->varDir(), 'project');
    unlink($project_dir);
    mkdir($project_dir, 0777, true);
    $project = new StatsProject(1, $project_dir, null, false);
    mkdir($project->getLogsDir(), 0777, true);
    $log_file = $project->getLogsDir() . '/one.log';
    
    $time = time();
    for($i = 0; $i < 1000; $i++)
      $helper->writeLog($log_file, $time, array('c' => 'visit', 'user' => $i.$i));
    $time = time() + 86400;
    for($i = 100; $i < 1002; $i++)
    {
      $helper->writeLog($log_file, $time, array('c' => 'visit', 'user' => $i.$i));
      $helper->writeLog($log_file, $time, array('c' => 'visit', 'user' => $i.$i));
    }

    $parser = new StatsLogParser($project);
    $parser->process();

    $metric = new StatsMetricVisit(new StatsProject(1, $project_dir, null, false));
    $stats = $metric->fetchReturnsForPeriod($time - 86400, $time);
    $this->assertEqual($stats[$time - 86400][0], 1000);
    $this->assertEqual($stats[$time - 86400][1], 900);
    $this->assertEqual($stats[$time][0], 2);
    
    $stats = $metric->fetchDauForPeriod($time - 86400, $time);
    $this->assertEqual($stats[$time], 902);
    $this->assertEqual($stats[$time - 86400], 1000);
  }

  function testWauMau()
  {
    $helper = test_stats();
    $project_dir = tempnam($helper->varDir(), 'project');
    unlink($project_dir);
    mkdir($project_dir, 0777, true);
    $project = new StatsProject(1, $project_dir, $this, false);
    mkdir($project->getLogsDir(), 0777, true);
    $log_file = $project->getLogsDir() . '/one.log';
    
    $by_days = array();
    $time = time();
    for($day = -100; $day <= 0; $day++)
    {
      $date = $time + $day*86400;
      $by_days[$date] = array();
      for($i = 0; $i < rand(0, 10); $i++)
      {
        $user_id = rand(1, 100);
        $by_days[$date][] = $user_id;
        $helper->writeLog($log_file, $date, array('c' => 'visit', 'user' => $user_id, 'ref' => rand(0, 1) ? 'oneone' : 'one'));
        $helper->writeLog($log_file, $date + 60, array('c' => 'visit', 'user' => $user_id, 'ref' => rand(0, 1) ? 'oneone' : 'one'));
      }
    }
    
    $parser = new StatsLogParser($project);
    $parser->process();

    $metric = new StatsMetricVisit(new StatsProject(1, $project_dir, $this, false));
    $stats = $metric->fetchUniqueForPeriod($time - 86400*30, $time);
    $ref_stats = $metric->getRefMetric()->fetchUniqueForPeriod($time - 86400*30, $time, 'one');
    for($day = -30; $day <= 0; $day++)
    {
      $date = $time + $day*86400;
      $this->assertEqual($stats[$date][1], count(array_unique($by_days[$date])));
      $this->assertEqual($ref_stats[$date][1], count(array_unique($by_days[$date])));
      $wau = array();
      for($t = 0; $t < 7; $t++)
        if(isset($by_days[$date - $t*86400]))
          foreach($by_days[$date - $t*86400] as $id)
            $wau[$id] = 1;
      $this->assertEqual($stats[$date][7], count($wau));
      $this->assertEqual($ref_stats[$date][7], count($wau));
      $mau = array();
      for($t = 0; $t < 30; $t++)
        if(isset($by_days[$date - $t*86400]))
          foreach($by_days[$date - $t*86400] as $id)
            $mau[$id] = 1;
      $this->assertEqual($stats[$date][30], count($mau));
      $this->assertEqual($ref_stats[$date][30], count($mau));
    }
  }

  function testRegistreStats()
  {
    require_once('bit/common.inc.php');
    $helper = test_stats();
    $project_dir = tempnam($helper->varDir(), 'project');
    unlink($project_dir);
    mkdir($project_dir, 0777, true);
    $project = new StatsProject(1, $project_dir, null, false);
    mkdir($project->getLogsDir(), 0777, true);
    $log_file = $project->getLogsDir() . '/one.log';
    
    $time = time();
    for($day = 0; $day < 61; $day++)
    {
      $helper->writeLog($log_file, $time - (86400*$day), array('c' => 'visit', 'user' => $day + 1));
      $helper->writeLog($log_file, $time - (86400*$day), array('c' => 'visit', 'user' => $day + 1));
    }
    
    $parser = new StatsLogParser($project);
    $parser->process();

    $metric = new StatsMetricVisit($project);
    $stats = $metric->fetchRegistreStats($time - 30*86400, $time);
    $this->assertEqual(31, count($stats));
    foreach($stats as $row)
    {
      $this->assertEqual($row[1], 1);
      $this->assertEqual($row[7], 7);
      $this->assertEqual($row[30], 30);
    }

    $stats = $metric->fetchRegistreStats($time - 33*86400, $time - 33*86400);
    $this->assertEqual($stats[$time - 33*86400], array(1 => 1, 7 => 7, 30 => 28));
    
    $stats = $metric->fetchRegistreStats($time - 120*86400, $time - 59*86400);
    $this->assertEqual($stats[$time - 60*86400], array(1 => 1, 7 => 1, 30 => 1));
    $this->assertEqual($stats[$time - 59*86400], array(1 => 1, 7 => 2, 30 => 2));

    $stats = $metric->fetchUniqueForPeriod($time, $time);
    $this->assertEqual($stats[$time], array(1 => 1, 7 => 7, 30 => 30));
  }

  function testActive()
  {
    require_once('bit/common.inc.php');
    $helper = test_stats();
    $project_dir = tempnam($helper->varDir(), 'project');
    unlink($project_dir);
    mkdir($project_dir, 0777, true);
    $project = new StatsProject(1, $project_dir, new StatsMetricVisitTest_InfoActive(), false);
    mkdir($project->getLogsDir(), 0777, true);
    $log_file = $project->getLogsDir() . '/one.log';
    
    $time = time();
    $helper->writeLog($log_file, $time - (86400*63), array('c' => 'visit', 'user' => 1));
    $helper->writeLog($log_file, $time - (86400*32), array('c' => 'visit', 'user' => 1));
    $helper->writeLog($log_file, $time - (86400*31), array('c' => 'visit', 'user' => 2));
    $helper->writeLog($log_file, $time, array('c' => 'visit', 'user' => 2));
    $helper->writeLog($log_file, $time, array('c' => 'visit', 'user' => 1));

    $parser = new StatsLogParser($project);
    $parser->process();

    $metric = new StatsMetricVisit($project);
    $this->assertEqual(1, $metric->fetchActiveByDay($time - 86400*32));
    $this->assertEqual(1, $metric->fetchNewActiveByDay($time - 86400*32));
    $this->assertEqual(2, $metric->fetchActiveByDay($time));
    $this->assertEqual(1, $metric->fetchNewActiveByDay($time));
  }

  function normalizeReferrer($id, $name)
  {
    if($name == 'oneone' || $name == 'one')
      return 'one';
  }

  function hasActiveStats()
  {
    return false;
  }
}

class StatsMetricVisitTest_InfoActive
{
  function hasActiveStats()
  {
    return true;
  }
}
