<?php

require_once(__DIR__.'/../setup.php');


$time = time();
if(isset($argv[1]))
  $time = strtotime($argv[1]." day");

$host = lmb_env_get('DB_HOST');
$user = lmb_env_get('DB_USER');
$pass = lmb_env_get('DB_PASS');
$db = new mysqli($host,$user,$pass,'stats2');
  
pay($db,$time);
cost($db,$time);
exit(0);

function cost($db,$time) {
  $time_start  = round($time/86400)-1;
  $time_stop   = round($time/86400);
  $time_start *= 86400;
  $time_stop  *= 86400;
  $db_answer = $db->query("CREATE TEMPORARY TABLE tmp_costByTask (SELECT id,project_id,ext_id,stamp,reg_time,value as pay FROM log_costStoack WHERE stamp>=$time_start and stamp<=$time_stop and item_id IN (108683766,37710838))");
  var_dump($db_answer);
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  $tables_db = $db->query("SELECT t.*,q.item_id as quest_id,MAX(q.stamp) as active_task FROM tmp_costByTask as t,log_QuestDone as q WHERE t.project_id=q.project_id and t.ext_id=q.ext_id and q.stamp<t.stamp GROUP BY q.ext_id,q.item_id");
  if(!$tables_db) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  $result = array();
  while($row = $tables_db->fetch_assoc()) {    
    $result[$row['id']] = $row;
  }
  foreach($result as $params) {
    unset($params['id']);
    $params['ext_id'] = "'".$params['ext_id']."'";
    $keys = implode(",",array_keys($params));
    $values = implode(",",array_values($params));

    $SQL = "INSERT INTO log_costBetweenTasks ($keys) VALUES ($values)";
    $db_answer = $db->query($SQL);
    var_dump($db_answer);
    if(!$db_answer) {
      echo "Error: ".$db->error."\r\n";
    }
    echo "SQL: $SQL\r\n";
  }
}
  
  
function pay($db,$time) {
  $time_start  = round($time/86400)-1;
  $time_stop   = round($time/86400);
  $time_start *= 86400;
  $time_stop  *= 86400;
  $db_answer = $db->query("CREATE TEMPORARY TABLE tmp_payByTask (SELECT id,project_id,ext_id,stamp,reg_time,value as pay FROM log_Pay WHERE stamp>=$time_start and stamp<=$time_stop)");
  var_dump($db_answer);
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  $tables_db = $db->query("SELECT t.*,q.item_id as quest_id,MAX(q.stamp) as active_task FROM tmp_payByTask as t,log_QuestDone as q WHERE t.project_id=q.project_id and t.ext_id=q.ext_id and q.stamp<t.stamp GROUP BY q.ext_id,q.item_id");
  if(!$tables_db) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  $result = array();
  while($row = $tables_db->fetch_assoc()) {    
    $result[$row['id']] = $row;
  }
  foreach($result as $params) {
    unset($params['id']);
    $params['ext_id'] = "'".$params['ext_id']."'";
    $keys = implode(",",array_keys($params));
    $values = implode(",",array_values($params));

    $SQL = "INSERT INTO log_payBetweenTasks ($keys) VALUES ($values)";
    $db_answer = $db->query($SQL);
    var_dump($db_answer);
    if(!$db_answer) {
      echo "Error: ".$db->error."\r\n";
    }
    echo "SQL: $SQL\r\n";
  }
}
