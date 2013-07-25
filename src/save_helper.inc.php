<?php

require_once('db_table_name_map.inc.php');
require_once('EnumEvents.inc.php');

const MAX_SAVED_LINES = 5;

function cronWorker()
{
  $timer = gme_pinba()->startTimer("Redis", "Redis_cron_worker");
  global $db_table_name_map;  
  $redis = new RedisLogger();
  $db = lmbActiveRecord::getDefaultConnection();
  $events = $redis->getSavedObjects(MAX_SAVED_LINES);
  $counter = 0;
  
  $groups = array();
  $timer_parse = gme_pinba()->startTimer("Redis", "Redis_parse_events");    
  foreach ($events as $event)
  {
    $timer1 = gme_pinba()->startTimer("Redis", "Redis_add_one_event");    
    $pos = strpos($event,"}");
    $project_id = substr($event, 0, $pos);
    $event = substr($event, $pos+1,  strlen($event)-$pos);
    $decode = json_decode($event,true);
    $event_id = $decode['event']*1;
    unset($decode['event']);

    if(!isset($groups[$event_id]))
      $groups[$event_id] = array();
    
    if(!isset($groups[$event_id][$project_id]))
      $groups[$event_id][$project_id] = array();
    
    $values = stat_normalize_event_data($decode, $project_id,$event_id);
    
    if($values)
      $groups[$event_id][$project_id][] = $values;
    
    gme_pinba()->stopTimer($timer1);
    $counter ++;
  }
  
  gme_pinba()->stopTimer($timer_parse);
  echo "Inserts parse count: ".$counter."\r\n";
  
  $counter = 0;
  $timer_mysql = gme_pinba()->startTimer("MySQL", "MySQL_add_to_mysql_cron");  
  
  foreach($groups as $event_id=>$projects){
    $sql_fields = "(".stat_get_db_fields($event_id).")";    
    foreach($projects as $p_id=>$values) {
      $timer_mysql_one = gme_pinba()->startTimer("MySQL", "MySQL_add_one_mysql");      
      $value = implode(",", $values);      
      $SQL = "INSERT INTO ".$db_table_name_map[$event_id]." ".$sql_fields." VALUES ".$value.";";
      $result = false;
//      echo "SQL: $SQL\r\n";
      $result = $db->execute($SQL);
      if(!$result) {
        $timer_error = gme_pinba()->startTimer("Redis", "Redis_cron_error");
        echo "Inserting error!\r\n";
        gme_pinba()->stopTimer($timer_error);
      } else
        $counter += count($values);
      gme_pinba()->stopTimer($timer_mysql_one);
    }
  }
  gme_pinba()->stopTimer($timer_mysql);
  gme_pinba()->stopTimer($timer);
  echo "Inserting count: ".$counter."\r\n";
}
