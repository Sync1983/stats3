<?php

class StatsLogParser
{
  protected $_project;
  protected $_manager;

  function __construct(StatsProject $project)
  {
    $this->_project = $project;
  }

  function process()
  {
    $logs = array();
    foreach(glob($this->_project->getLogsDir() . '/*.log') as $log)
      $logs[basename($log, '.log')] = $log;
    ksort($logs, SORT_NUMERIC);
    foreach($logs as $log)
      $this->_parseLog($log);
    while(count($logs) > 1)
    {
      // TODO вращение не по количеству а по дате
      $log = array_shift($logs); 
      $gz_file = $log . '.gz';
      if(file_exists($gz_file))
        unlink($gz_file);
      $gzstream = gzopen($gz_file, 'wb3');
      $rstream = fopen($log, 'r');
      stream_copy_to_stream($rstream, $gzstream);
      fclose($rstream);
      gzclose($gzstream);
      unlink($log);
    }

    $logs = array();
    foreach(glob($this->_project->getLogsDir() . '/*.log.gz') as $log)
      $logs[basename($log, '.log.gz')] = $log;
    ksort($logs, SORT_NUMERIC);
    while(count($logs) > 5)
      unlink(array_shift($logs)); 
    $this->_project->closeDbs();
  }

  protected function _parseLog($log_file)
  {
    $reader = new StastLogReader($this->_project->getDbLogPosition(), $log_file);
    $metric_visit = $this->_project->getStatsMetricVisit();
    $metric_counters= $this->_project->getStatsMetricCounters();
    while($line = $reader->readNextLine())
    {
      if(!($data = $this->_parseLogLine($line))) 
        continue;
      list($date, $query) = $data;
      if(!isset($query['c']))
        continue;
      switch($query['c']) // category
      {
        case 'visit':
          if(isset($query['user']) && $query['user'])
            $metric_visit->onVisit($date, $query['user'], isset($query['ref']) ? $query['ref'] : null);
          break;
        case 'counter':
          if(isset($query['d']) && is_array($query['d']))
            $metric_counters->onIncrement($date, $query['d']);
          break;
      }
    }
    $metric_counters->flush();
    $metric_visit->flush(); 
  }

  protected function _parseLogLine($line)
  {
    $data = explode(',', $line);
    if(count($data) < 2)
      return false;   

    // date
    // 13/Sep/2010:16:40:11 +0400
    //$day = explode(':', $data[0]);
    //$day = $day[0] . ':00:00:00' . substr($day[3], 2);
    //$data[0] = strtotime($day);

    $data[0] = strtotime($data[0]);

    if(!$data[0])
      return false;
    parse_str($data[1], $data[1]);
    if(!is_array($data[1]))
      return false;
    return $data;
  }
}
