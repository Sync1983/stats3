<?php

class StatsUniqueRegister
{
  const VISIT_NEW = 'n';
  const VISIT_DAY = 'd';
  const VISIT_WEEK = 'w';
  const VISIT_MONTH = 'm';

  private $_counter;
  private $_db_time;

  function __construct(DbaWrapper $db_counters, DbaWrapper $db_time)
  {
    $this->_counter = new DbaIncrement($db_counters);
    $this->_db_time = $db_time;
  }

  function flush()
  {
    $this->_counter->flush();
  }

  function onVisit($date, $user_id, $ref, $is_new)
  {
    $day = date('Ymd', $date);
    $last_visit = $this->_db_time->get($user_id . '.' . $ref);
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

    $key = $day . $ref;

    $left_days = ($date - $last_visit) / 86400;
    if($is_new)
      $this->_counter->increment(self :: VISIT_NEW . $key, 1);
    if($left_days >= 1 || ($day !== $day_last_visit))
    {
      $this->_counter->increment(self :: VISIT_DAY. $key, 1);
      $last_visit = $date;
    }
    
    if(($date - $last_week) >= 86400)
    {
      $offset = min(($date - $last_week) / 86400, 7);
      for($i = 7 - $offset; $i < 7; $i++)
        $this->_counter->increment(self :: VISIT_WEEK . date('Ymd', $date + $i*86400) . $ref, 1);
      $last_week = $date;
    }
    if(($date - $last_month) >= 86400)
    {
      $offset = min(($date - $last_month) / 86400, 30);
      for($i = 30 - $offset; $i < 30; $i++)
        $this->_counter->increment(self :: VISIT_MONTH . date('Ymd', $date + $i*86400) . $ref, 1);
      $last_month = $date;
    }
    $this->_db_time->set($user_id . '.' . $ref, pack('VVV', $last_visit, $last_week, $last_month));
  }

  function fetchNewStats($start_day, $end_day, $ref)
  {
    $all_stats = array();
    $db = $this->_counter->getDb();
    $days = ceil(($end_day - $start_day) / 86400);
    for($day = $days + 30; $day >= 0; $day--)
      $all_stats[] = $db->get(self :: VISIT_NEW . date('Ymd', $end_day - ($day * 86400)) . $ref);

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

  function fetchUniqueForPeriod($start_day, $end_day, $ref)
  {
    $stats = array();
    $db = $this->_counter->getDb();
    for($date = $start_day; $date <= $end_day; $date += 86400)  
    {
      $dau = $db->get(self :: VISIT_DAY . date('Ymd', $date) . $ref);
      $wau = $db->get(self :: VISIT_WEEK . date('Ymd', $date) . $ref);
      $mau = $db->get(self :: VISIT_MONTH . date('Ymd', $date) . $ref);
      $stats[$date] = array(1 => $dau, 7 => $wau, 30 => $mau);
    }
    return $stats;
  }
}
