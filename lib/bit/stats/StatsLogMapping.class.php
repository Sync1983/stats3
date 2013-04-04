<?php

class StatsLogMapping
{
  protected $_manager;

  function __construct(StatsProjectsManager $manager)
  {
    $this->_manager = $manager;      
  }

  function process($log_file)
  {
    $log_db = new DbaWrapper($this->_manager->getBaseDir() . '/global_log.db');
    $reader = new StastLogReader($log_db, $log_file);
    
    $count = 0;
    $lines = array();
    while($line = $reader->readNextLine())
    {
      list($project_id, $data) = explode(',', $line, 2);
      $lines[$project_id][] = $data;
      if(++$count > 3000) // < 21 Mb
      {
        $count = 0;
        $this->_mapLines($lines);
        $lines = array();
      }
    }
    $this->_mapLines($lines);
    unset($lines);
  }

  protected function _mapLines($to_project)
  {
    foreach($to_project as $project_id => $lines)
    {
      if(!$this->_manager->hasProject($project_id))
        continue;
      if(!$log = $this->_manager->getProjectLog($project_id))
        continue;
      file_put_contents($log, implode("\n", $lines) . "\n", FILE_APPEND);
    }
  }
}
