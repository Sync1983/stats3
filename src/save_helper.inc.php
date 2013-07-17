<?php

require_once('db_table_name_map.inc.php');

if(file_exists(__DIR__.'/EnumLoggerEvent.class.php'))
  require_once(__DIR__.'/EnumLoggerEvent.class.php');

const MAX_SAVED_LINES = 50000;

function cronWorker()
{
  $timer = gme_pinba()->startTimer("Redis", "Redis_cron_worker");
  global $db_table_name_map;  
  $redis = new RedisLogger();
  $db = lmbActiveRecord::getDefaultConnection();
  // Example
  //$result = $db->execute("SELECT preset.*,page_view.* FROM preset,page_view WHERE page_view.counter_id=preset.id and page_view.page_id=$page_id and page_view.data_type=0;");
  
  $events = $redis->getSavedObjects(MAX_SAVED_LINES);
  $counter = 0;
  
  $groups = array();
  $timer_parse = gme_pinba()->startTimer("Redis", "Redis_add_one_event");    
  foreach ($events as $event)
  {
    $timer1 = gme_pinba()->startTimer("Redis", "Redis_add_one_event");    
    $pos = strpos($event,"}");
    $project_id = substr($event, 0, $pos);
    $event = substr($event, $pos+1,  strlen($event)-$pos);
    $decode = json_decode($event,true);
    $event_id = $decode['event']*1;
    unset($decode['event']);
    $event_name = EnumLoggerEvent::getNameByValue($event_id);

    if(!isset($groups[$event_name]))
      $groups[$event_name] = array();
    
    if(!isset($groups[$event_name][$project_id]))
      $groups[$event_name][$project_id] = array();
    $values = stat_normalize_event_data($event_name, $decode, $project_id);
    if($values)
      $groups[$event_name][$project_id][] = $values;
    
    gme_pinba()->stopTimer($timer1);
    $counter ++;
  }
  
  gme_pinba()->stopTimer($timer_parse);
  echo "Inserts parse count: ".$counter."\r\n";
  
  $counter = 0;
  $timer_mysql = gme_pinba()->startTimer("MySQL", "MySQL_add_to_mysql_cron");  
  
  foreach($groups as $event_name=>$projects){
    $fields = stat_get_db_fields($event_name);
    $sql_fields = "(`".implode("`,`", $fields)."`)";    
    foreach($projects as $p_id=>$values) {
      $timer_mysql_one = gme_pinba()->startTimer("MySQL", "MySQL_add_one_mysql");
      $value = implode(",", $values);      
      $SQL = "INSERT INTO ".$db_table_name_map[$event_name]." ".$sql_fields." VALUES ".$value.";";
      $result = false;
      $result = $db->execute($SQL);
      if(!$result) {
        $timer_error = gme_pinba()->startTimer("Redis", "Redis_cron_error");
        echo "Inserting error!\r\n";
        gme_pinba()->stopTimer($timer_error);
      }
      $counter += count($values);
      gme_pinba()->stopTimer($timer_mysql_one);
    }
  }
  gme_pinba()->stopTimer($timer_mysql);
  echo "Inserting count: ".$counter."\r\n";
}
