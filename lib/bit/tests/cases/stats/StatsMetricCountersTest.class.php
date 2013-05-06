<?php

class StatsMetricCountersTest extends UnitTestCase
{
  function testIncrement()
  {
    $helper = test_stats();
    $project_dir = tempnam($helper->varDir(), 'project');
    unlink($project_dir);
    mkdir($project_dir, 0777, true);

    $info = new StatsMetricCountersTest_Info($project_dir);
    $info->projects = array(1);
    $info->counters = array('money', 'real');

    $manager = new StatsProjectsManager($info);
    $manager->is_ready_only = false;
    $project = $manager->getProject(1);
    $this->assertTrue($project);
    
    mkdir($project->getLogsDir(), 0777, true);
    $log_file = $project->getLogsDir() . '/one.log';

    $time = time();
    $helper->writeLog($log_file, $time, array('c' => 'counter', 'd[money]' => 2));
    for($i = 0; $i < 10; $i++)
      $helper->writeLog($log_file, $time + 86400, array('c' => 'counter', 'd[money]' => 1, 'd[real]' => 3));
    
    $parser = new StatsLogParser($project);
    $parser->process();

    $stats = $project->getStatsMetricCounters()->fetchCounter('money', $time, $time + 86400);
    $this->assertEqual(count($stats), 2);
    $this->assertEqual($stats[$time], 2);
    $this->assertEqual($stats[$time + 86400], 10);
    
    $stats = $project->getStatsMetricCounters()->fetchCounter('real', $time, $time + 86400);
    $this->assertEqual($stats[$time + 86400], 30);
  }
}

class StatsMetricCountersTest_Info implements StatsProjectsInfo
{
  public $projects = array();
  public $counters = array();
  public $base_dir;

  function __construct($base_dir)
  {
    $this->base_dir = $base_dir;
  }

  function getBaseDir()
  {
    return $this->base_dir;
  }

  function getProjectDir($id)
  {
    return $this->base_dir . '/' . $id;
  }

  function hasProject($id)
  {
    return in_array($id, $this->projects);
  }

  function getNextProjectUid($id)
  {
  }

  function hasCounter($id, $name)
  {
    return in_array($name, $this->counters);
  }

  function normalizeReferrer($id, $name)
  {
    return $name;
  }

  function hasActiveStats($project_id)
  {
    return false;
  }
}

