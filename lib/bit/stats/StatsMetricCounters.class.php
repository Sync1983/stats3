<?php

class StatsMetricCounters
{
  protected $_project;
  protected $_counter;
  protected $_has_cache;

  function __construct(StatsProject $project)  
  {
    $this->_project = $project;
    $this->_counter = new DbaIncrement($this->_project->getDbCounters());
    $this->_has_cache = new HashMapFixedSize(10000);
  }

  function onIncrement($date, $counters)
  {
    $date = date('Ymd', $date);
    foreach($counters as $name => $value)
    {
      if(!$this->_hasCounter($name) || !is_numeric($value))
        continue;
      $this->_counter->increment($date . $name, $value);
    }
  }

  function rawSetCounter($date, $name, $value)
  {
    $this->_counter->set(date('Ymd', $date) . $name, $value);
  }

  function flush()
  {
    $this->_counter->flush();
  }

  function fetchCounter($name, $start_day, $end_day)
  {
    $stats = $this->fetchCounters(array($name), $start_day, $end_day, true);
    return isset($stats[$name]) ? $stats[$name] : array();
  }

  function fetchCounters($names, $start_day, $end_day, $by_names = true)
  {
    $stats = array();

    $stats = $this->_fetchStandartCounters($names, $start_day, $end_day, $by_names);

    $db = $this->_project->getDbCounters();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
    {
      $ymd = date('Ymd', $date);
      foreach($names as $name)
      {
        if($by_names)
          $stats[$name][$date] = $db->get($ymd . $name);
        else
          $stats[$date][$name] = $db->get($ymd . $name);
      }
    }
    return $stats;
  }

  private function _fetchStandartCounters(&$names, $start_day, $end_day, $by_names)
  {
    $stats = array();
    $mau = BitStatsApiClient :: COUNTER_MAU;
    $pos = array_search($mau, $names);
    if(false !== $pos)
    {
      unset($names[$pos]);
      foreach($this->_project->getStatsMetricVisit()->fetchMau($start_day, $end_day) as $date => $value)
      {
        if($by_names)
          $stats[$mau][$date] = $value;
        else
          $stats[$date][$mau] = $value;
      }
    }
    $dau = BitStatsApiClient :: COUNTER_DAU;
    $pos = array_search($dau, $names);
    if(false !== $pos)
    {
      unset($names[$pos]);
      foreach($this->_project->getStatsMetricVisit()->fetchDauForPeriod($start_day, $end_day) as $date => $value)
      {
        if($by_names)
          $stats[$dau][$date] = $value;
        else
          $stats[$date][$dau] = $value;
      }
    }
    return $stats;
  }

  private function _hasCounter($name)
  {
    $value = $this->_has_cache->get($name);
    if(null === $value)
    {
      $value = (bool) $this->_project->hasCounter($name);
      $this->_has_cache->set($name, $value);
    }
    return $value;
  }
}

