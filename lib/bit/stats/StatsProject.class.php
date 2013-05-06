<?php

class StatsProject
{
  protected $_id;
  protected $_base_dir;
  protected $_dbs;
  protected $_info;
  protected $_read_only = true;

  private $_mvisit;
  private $_mcountes;

  function __construct($id, $dir, $info = null, $read_only = true)
  {
    $this->_id = $id;
    $this->_base_dir = $dir;     
    $this->_info = $info;
    $this->_read_only = $read_only;
  }

  function getLogsDir()
  {
    return $this->_base_dir . '/logs';
  }

  function getDbLogPosition()
  {
    return $this->_getDb('logs');
  }
  
  function getDbRegistre()
  {
    return $this->_getDb('registre');
  }

  function getDbHasReturn($reg_time)
  {
    return $this->_getDb('has_visit', $reg_time);
  }
  
  function getDbRegActive()
  {
    return $this->_getDb('reg_active');
  }
  
  function getDbCounters()
  {
    return $this->_getDb('counters');
  }
  
  function getDbReturn()
  {
    return $this->_getDb('return');
  }

  function getDbDau()
  {
    return $this->_getDb('dau');
  }
  
  function getDbVisit()
  {
    return $this->_getDb('visit');
  }
  
  function getDbVisitCounters()
  {
    return $this->_getDb('visit_counters');
  }
  
  function getDbRefVisit()
  {
    return $this->_getDb('ref_visit');
  }
  
  function getDbRefVisitCounters()
  {
    return $this->_getDb('ref_visit_counters');
  }

  function hasCounter($name)
  {
    return $this->_info->hasCounter($this->_id, $name);
  }
  
  function normalizeReferrer($name)
  {
    return $this->_info->normalizeReferrer($this->_id, $name);
  }
  
  function hasActiveStats()
  {
    return $this->_info && $this->_info->hasActiveStats($this->_id);
  }

  function getStatsMetricVisit()
  {
    if(null === $this->_mvisit)
      $this->_mvisit = new StatsMetricVisit($this);
    return $this->_mvisit;
  }
  
  function getStatsMetricCounters()
  {
    if(null === $this->_mcountes)
      $this->_mcountes = new StatsMetricCounters($this);
    return $this->_mcountes;
  }

  function closeDbs()
  {
    if(!$this->_dbs)
      return;
    foreach($this->_dbs as $key => $db)
    {
      if($db) 
        $db->close();
      unset($this->_dbs[$key]);
    }
  }

  protected function _getDb($name, $date = null)
  {
    $key = $name . '_' . ($date ? date('Ymd', $date) : '') . '_'; 
    if(!isset($this->_dbs[$key]))
    {
      $name = strtr($name, array('/' => '-', '.' => '_'));
      if($date === null)
        $file = $this->_base_dir . '/' . $name . '.db';
      else
      {
        $dir = $this->_base_dir . '/' . $name  . '/' . date('Y', $date);
        if(!file_exists($dir) && !mkdir($dir, 0777, true))
          throw new Exception("Failed create dir: " . $dir);
        $file = $dir . '/' . date('md', $date) . '.db';
      }

      if($this->_read_only && !file_exists($file))
        dba_close(dba_open($file, 'c', 'db4'));

      $this->_dbs[$key] = new DbaWrapper($file, 'db4', $this->_read_only ? 'r-' : 'c');
    }
    return $this->_dbs[$key];
  }
}
