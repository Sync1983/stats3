<?php

class StatsMetricVisit
{
  const VISIT_NEW = 'n';
  const VISIT_DAY = 'd';
  const VISIT_WEEK = 'w';
  const VISIT_MONTH = 'm';
  const VISIT_ACTIVE = 'a';
  const VISIT_NEW_ACTIVE = 'b';

  private $_refs = array();

  function __construct(StatsProject $project)
  {
    $this->_project = $project;
    $this->_reg_db = $this->_project->getDbRegistre();
    $this->_reg_active_db = $this->_project->getDbRegActive();
    $this->_return_counter = new DbaCounter($this->_project->getDbReturn());
    $this->_dau_counter = new DbaCounter($this->_project->getDbDau());

    $this->_visit_counters = new DbaIncrement($this->_project->getDbVisitCounters());
    $this->_visit_db = $this->_project->getDbVisit();

    $this->_ref_metric = new StatsUniqueRegister($project->getDbRefVisit(), $project->getDbRefVisitCounters());
    $this->_has_active_stats = $project->hasActiveStats();
  }

  function getRefMetric()
  {
    return $this->_ref_metric;
  }

  function flush()
  {
    $this->_visit_counters->flush();
    $this->_ref_metric->flush();
  }

  function onVisit($date, $user_id, $ref = null)
  {
    $day = date('Ymd', $date);
    $last_visit = $this->_visit_db->get($user_id);
    if($last_visit)
    {
      if(strlen($last_visit) != 12)
      {
        $last_visit = pack('V', (int) $last_visit);
        $last_visit = $last_visit.pack('V', 0).pack('V', 0);
      }
      $lvisit = unpack('V*', $last_visit);
    }
    else
      $lvisit = array(0, 0, 0, 0);
    list(,$last_visit, $last_week, $last_month) = $lvisit;

    $day_last_visit = $last_visit ? date('Ymd', $last_visit) : '';
    
    if($ref && ($ref = $this->_normalizeRef($ref)))
      $this->_ref_metric->onVisit($date, $user_id, $ref, !$last_visit);

    $left_days = ($date - $last_visit) / 86400;
    if(!$last_visit)
      $this->_visit_counters->increment(self :: VISIT_NEW . $day, 1);
    if($left_days >= 1 || ($day !== $day_last_visit))
    {
      $this->_dau_counter->increment($day);
      $this->_visit_counters->increment(self :: VISIT_DAY. $day, 1);
      $last_visit = $date;
    }
    if(($date - $last_week) >= 86400)
    {
      $offset = min(($date - $last_week) / 86400, 7);
      for($i = 7 - $offset; $i < 7; $i++)
        $this->_visit_counters->increment(self :: VISIT_WEEK . date('Ymd', $date + $i*86400), 1);
      $last_week = $date;
    }
    if(($date - $last_month) >= 86400)
    {
      $offset = min(($date - $last_month) / 86400, 30);
      for($i = 30 - $offset; $i < 30; $i++)
        $this->_visit_counters->increment(self :: VISIT_MONTH . date('Ymd', $date + $i*86400), 1);
      $last_month = $date;
    }

    $this->_visit_db->set($user_id, pack('VVV', $last_visit, $last_week, $last_month));
    
    // return
    $reg_time = $this->_reg_db->get($user_id);
    if(false === $reg_time)
    {
      $reg_time = $date;
      $this->_reg_db->set($user_id, $date);
    }
    $day = (int) (($this->_getBeginDay($date) - $this->_getBeginDay($reg_time)) / 86400);
    if($day <= 30)
    {
      $db_return = $this->_project->getDbHasReturn($reg_time);
      $visit_key = $user_id . $day;
      if(!$db_return->has($visit_key))
      {
        $db_return->set($visit_key, 1);
        $this->_return_counter->increment(date('Ymd', $reg_time) . $day);
      }
    }
    elseif($this->_has_active_stats)
    {
      $last_reg_active = $this->_reg_active_db->get($user_id);
      if(!$last_reg_active || date('Ym', $last_reg_active) != date('Ym', $date))
      {
        $this->_reg_active_db->set($user_id, $date);
        $this->_visit_counters->increment(self :: VISIT_ACTIVE . date('Ymd', $date), 1);
        if(!$last_reg_active)
          $this->_visit_counters->increment(self :: VISIT_NEW_ACTIVE . date('Ymd', $date), 1);
      }
    }
  }

  private function _normalizeRef($name)
  {
    if(!array_key_exists($name, $this->_refs))  
      $this->_refs[$name] = $this->_project->normalizeReferrer($name);
    return $this->_refs[$name];
  }

  private function _getBeginDay($timestamp)
  {
    static $time = null; 
    if(null == $time)
      $time = new DateTime(false);
    $time->setTimestamp($timestamp);
    $time->setTime(0, 0, 0);
    return $time->format('U');
  }

  function fetchReturnsForPeriod($start_reg, $end_reg)
  {
    $stats = array();
    $db = $this->_return_counter->getDb();
    for($date = $start_reg; $date <= $end_reg; $date += 86400)  
    {
      $reg_day = date('Ymd', $date);
      $for_date = array();
      for($day = 0; $day < 31; $day++)
        $for_date[$day] = (int) $db->get($reg_day . $day);
      $stats[$date] = $for_date;
    }
    return $stats;
  }

  function fetchDauForPeriod($start_day, $end_day)
  {
    $stats = array();
    $db = $this->_dau_counter->getDb();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
      $stats[$date] = $db->get(date('Ymd', $date));
    return $stats;
  }

  function fetchRegistreStats($start_day, $end_day)
  {
    $all_stats = array();
    $db = $this->_visit_counters->getDb();
    $days = ceil(($end_day - $start_day) / 86400);
    for($day = $days + 30; $day >= 0; $day--)
      $all_stats[] = $db->get(self :: VISIT_NEW . date('Ymd', $end_day - ($day * 86400)));

    $stats = array();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
    {
      $key = $days - floor(($end_day - $date) / 86400) + 30;
      $stats[$date][1] = $all_stats[$key];
      $stats[$date][7] = array_sum(array_slice($all_stats, $key-6, 7));
      $stats[$date][30] = array_sum(array_slice($all_stats, $key-29, 30));
    }
    return $stats;
  }

  function fetchUniqueForPeriod($start_day, $end_day)
  {
    $stats = array();
    $db = $this->_visit_counters->getDb();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
    {
      $dau = $db->get(self :: VISIT_DAY . date('Ymd', $date));
      $wau = $db->get(self :: VISIT_WEEK . date('Ymd', $date));
      $mau = $db->get(self :: VISIT_MONTH . date('Ymd', $date));
      $stats[$date] = array(1 => $dau, 7 => $wau, 30 => $mau);
    }
    return $stats;
  }

  function fetchMau($start_day, $end_day)
  {
    $stats = array();
    $db = $this->_visit_counters->getDb();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
      $stats[$date] = $db->get(self :: VISIT_MONTH. date('Ymd', $date));
    return $stats;
  }
  
  function fetchWau($start_day, $end_day)
  {
    $stats = array();
    $db = $this->_visit_counters->getDb();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
      $stats[$date] = $db->get(self :: VISIT_WEEK. date('Ymd', $date));
    return $stats;
  }

  function fetchActiveByDay($day)
  {
    return $this->_visit_counters->getDb()->get(self :: VISIT_ACTIVE . date('Ymd', $day));
  }
  
  function fetchNewActiveByDay($day)
  {
    return $this->_visit_counters->getDb()->get(self :: VISIT_NEW_ACTIVE . date('Ymd', $day));
  }
}

