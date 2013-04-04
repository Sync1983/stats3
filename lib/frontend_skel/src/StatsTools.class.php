<?php

class StatsTools extends lmbAbstractTools
{
  private $tz;

  function getStatsManager()
  {
    static $manager = null;
    if(null === $manager)
      $manager = new StatsProjectsManager(new StatsProjectsInfoDb);
    return $manager;
  }
  
  function getTimeZone()
  {
    if(null === $this->tz)
      $this->tz = new DateTimeZone(date_default_timezone_get());
    return $this->tz;
  }

  function setTimeZone($tz)
  {
    $this->tz = $tz;
  }
  
  function getDayForString($string)
  {
    if(!$string)
      return false;
    $items = explode('.', $string, 3);
    if(count($items) != 3)
      return false;
    foreach(array(0, 1) as $k)
    if(strlen($items[$k]) < 2)
      $items[$k] = str_pad($items[$k], 2, '0', STR_PAD_LEFT);
    $date = new DateTime('now', $this->getTimeZone());
    $date->setDate($items[2], $items[1], $items[0]);
    $date->setTime(0, 0, 0);
    if($date->format('d.m.Y') == implode('.', $items))
      return (int) $date->format('U');
    return false;
  }

  function getBeginDay($timestamp)
  {
    $time = new DateTime(false, $this->getTimeZone());
    $time->setTimestamp((int) $timestamp);
    $time->setTime(0, 0, 0);
    return $time->format('U');
  }
 
  function getCurrentDay($cache = true)
  {
    static $last = 0, $day;
    if(!$cache || ($last + 60 < time()))
    {
      $last = time();
      $time = new DateTime('now', $this->getTimeZone());
      $time->setTime(0, 0, 0);
      $day = (int) $time->format('U');
    }
    return $day;
  }
    
  function getCurrentDayUTC()
  {
    static $last = 0, $day;
    if($last + 60 < time())
    {
      $last = time();
      $time = new DateTime('@'.$last);
      $time->setTime(0, 0, 0);
      $day = (int) $time->format('U');
    }
    return $day;
  }

  function normalizeRequestPeriod($field_start, $field_end, $default_start, $default_end, $default_offset, $max_offset, $min_date = false)
  { 
    $request = $this->toolkit->getRequest();
    $r_begin_day = $request->getRequest($field_end);
    $r_end_day = $request->getRequest($field_start);

    $r_begin_day = $this->toolkit->getDayForString($r_begin_day);
    $r_end_day = $this->toolkit->getDayForString($r_end_day);
    if(!$r_begin_day && !$r_end_day)
    {
      $r_begin_day = $default_start;
      $r_end_day = $default_end;
    }
    if(!$r_begin_day && $r_end_day)
      $r_begin_day = $r_end_day - $default_offset;
    elseif($r_begin_day && !$r_end_day)
      $r_end_day = $r_begin_day + $default_offset;
    elseif($r_end_day < $r_begin_day)
    {
      $v = $r_begin_day;
      $r_begin_day = $r_end_day;
      $r_end_day = $v;
    }
    if($r_end_day - $r_begin_day > $max_offset)
      $r_begin_day = $r_end_day - $max_offset;
    if($min_date && $r_begin_day < $min_date)
      $r_begin_day = $min_date;
    return array($r_begin_day, $r_end_day);
  }
}
