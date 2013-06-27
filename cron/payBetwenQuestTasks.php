<?php

require_once(__DIR__.'/../setup.php');


$time_stamp = time();
if(isset($argv[1]))
  $time_stamp = strtotime($argv[1]." day");

$host = lmb_env_get('DB_HOST');
$user = lmb_env_get('DB_USER');
$pass = lmb_env_get('DB_PASS');
$db = new mysqli($host,$user,$pass,'stats2');

$time = getdate($time_stamp);
$time_start = mktime(0,0,0,$time["mon"],$time["mday"],$time["year"]);
$time_start -= 1*86400;
$time_stop = mktime(23,59,59,$time["mon"],$time["mday"],$time["year"]);  

pay($db,$time_start,$time_stop);
cost($db,$time_start,$time_stop);
exit(0);

<<<<<<< HEAD
function cost($db,$time_start,$time_stop) {
  $db_answer = $db->query("SELECT ext_id,item_id,stamp,project_id FROM log_QuestDone WHERE stamp>=$time_start and stamp<=$time_stop ORDER BY ext_id,stamp");

=======
function cost($db,$time) {
  $time_start  = round($time/86400)-1;
  $time_stop   = round($time/86400);
  $time_start *= 86400;
  $time_stop  *= 86400;
  $db_answer = $db->query("CREATE TEMPORARY TABLE tmp_costByTask (SELECT id,project_id,ext_id,stamp,reg_time,value as pay FROM log_costStock WHERE stamp>=$time_start and stamp<=$time_stop and item_id IN (108683766,37710838))");
  var_dump($db_answer);
>>>>>>> parent of 404d956... pre merge
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  $ranges = array();
  $pays = array();
  while($row = $db_answer->fetch_assoc()) {
    $ext_id = $row['ext_id'];
    $item_id = $row['item_id'];
    $stamp = $row['stamp'];
    $project_id = $row['project_id'];
    if(!isset($ranges[$project_id]))
      $ranges[$project_id] = array();
    if(!isset($ranges[$project_id]['"'.$ext_id.'"']))
      $ranges[$project_id]['"'.$ext_id.'"'] = array();
    $ranges[$project_id]['"'.$ext_id.'"'][$stamp]=$item_id; 
  }

  $db_answer = $db->query("SELECT ext_id,value,stamp,project_id FROM log_costStock WHERE stamp>=".($time_start-30*86400)." and item_id IN (108683766,37710838) ORDER BY ext_id,stamp;");
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  
  while($row=$db_answer->fetch_assoc()) {
    $ext_id = $row['ext_id'];
    $value = $row['value'];
    $stamp = $row['stamp'];
    $project_id = $row['project_id'];

    if(!isset($pays[$project_id]))
      $pays[$project_id] = array();
    if(!isset($pays[$project_id]['"'.$ext_id.'"']))
      $pays[$project_id]['"'.$ext_id.'"'] = array();
    if(!isset($pays[$project_id]['"'.$ext_id.'"'][$stamp]))  
      $pays[$project_id]['"'.$ext_id.'"'][$stamp] = $value; 
    else
      $pays[$project_id]['"'.$ext_id.'"'][$stamp] += $value;
  }

  $result = array();
  foreach($ranges as $pid=>&$users) {
    $result[$pid]=array();
    $pay_users = $pays[$pid];
    foreach($users as $user=>&$range_list) {
      $pay_range_list = $pay_users[$user];
      if(!is_array($pay_range_list))
        continue;
      ksort($pay_range_list);
      ksort($range_list);
      $tmp = array();
      foreach($range_list as $stamp=>$item_id) {
        $tmp[$item_id] = array();
        foreach($pay_range_list as $pay_stamp=>$pay_value) {
          if($pay_stamp<=$stamp) {     
            $tmp[$item_id][$pay_stamp] = $pay_value;
            unset($pay_range_list[$pay_stamp]);
          }
        }
        if(count($tmp[$item_id])==0)
          unset($tmp[$item_id]);
      }
      $result[$pid][$user] = $tmp;
    }
  }
  $SQL = "";
  foreach($result as $pid=>&$users){
    $SQL = "INSERT INTO log_costBetweenTasks (`project_id`,`ext_id`,`reg_time`,`stamp`,`pay`,`quest_id`) VALUES ";

    foreach($users as $ext_id=>&$items) {
      $SQL = "INSERT INTO log_costBetweenTasks (`project_id`,`ext_id`,`reg_time`,`stamp`,`pay`,`quest_id`) VALUES ";
      foreach($items as $item=>&$stamps)
        foreach($stamps as $stamp=>&$value) 
          $SQL .= "($pid,$ext_id,0,$stamp,$value,$item),";
      $SQL = substr($SQL,0,-1).";";
      $db_answer = $db->query($SQL);
      var_dump($db_answer);
      if(!$db_answer) {
       echo "SQL: $SQL\r\n";      
        echo "Error: ".$db->error."\r\n\r\n\r\n";
      }
    }
  }

  $SQL = "SELECT reg_time,ext_id,project_id FROM log_NewPlayer where ext_id in (select ext_id from log_costBetweenTasks WHERE reg_time = 0);";
  $db_answer = $db->query($SQL);
  var_dump($db_answer);
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n\r\n\r\n";
  }

  $result = array();
  while($row=$db_answer->fetch_assoc()) {
    $ext_id = $row['ext_id'];
    $reg_time = $row['reg_time'];
    $project_id = $row['project_id'];
    $result[$project_id.$ext_id] = array($ext_id,$reg_time,$project_id);
  }

  foreach($result as $item){
    $SQL = "UPDATE log_costBetweenTasks SET reg_time=".$item[1]." WHERE project_id=".$item[2]." and ext_id=\"".$item[0]."\";";
    $db_answer = $db->query($SQL);
    var_dump($db_answer);
    if(!$db_answer) {
      echo "SQL: $SQL\r\n";      
      echo "Error: ".$db->error."\r\n\r\n\r\n";
    }
<<<<<<< HEAD
=======
    echo "SQL: $SQL\r\n";
>>>>>>> parent of 404d956... pre merge
  }
}
  
  
function pay($db,$time_start,$time_stop) {
  $db_answer = $db->query("SELECT ext_id,item_id,stamp,project_id FROM log_QuestDone WHERE stamp>=$time_start and stamp<=$time_stop ORDER BY ext_id,stamp");
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  $ranges = array();
  $pays = array();
  while($row = $db_answer->fetch_assoc()) {
    $ext_id = $row['ext_id'];
    $item_id = $row['item_id'];
    $stamp = $row['stamp'];
    $project_id = $row['project_id'];
    if(!isset($ranges[$project_id]))
      $ranges[$project_id] = array();
    if(!isset($ranges[$project_id]['"'.$ext_id.'"']))
      $ranges[$project_id]['"'.$ext_id.'"'] = array();
    $ranges[$project_id]['"'.$ext_id.'"'][$stamp]=$item_id; 
  }

  $db_answer = $db->query("SELECT ext_id,value,stamp,project_id FROM log_Pay WHERE stamp>=".($time_start-30*86400)." ORDER BY ext_id,stamp;");
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n";
    return;
  }
  
  while($row=$db_answer->fetch_assoc()) {
    $ext_id = $row['ext_id'];
    $value = $row['value'];
    $stamp = $row['stamp'];
    $project_id = $row['project_id'];

    if(!isset($pays[$project_id]))
      $pays[$project_id] = array();
    if(!isset($pays[$project_id]['"'.$ext_id.'"']))
      $pays[$project_id]['"'.$ext_id.'"'] = array();
    if(!isset($pays[$project_id]['"'.$ext_id.'"'][$stamp]))  
      $pays[$project_id]['"'.$ext_id.'"'][$stamp] = $value; 
    else
      $pays[$project_id]['"'.$ext_id.'"'][$stamp] += $value;
  }

  print_r($pays);

  $result = array();
  foreach($ranges as $pid=>&$users) {
    $result[$pid]=array();
    $pay_users = $pays[$pid];
    foreach($users as $user=>&$range_list) {
      $pay_range_list = $pay_users[$user];
      if(!is_array($pay_range_list))
        continue;
      ksort($pay_range_list);
      ksort($range_list);
      $tmp = array();
      foreach($range_list as $stamp=>$item_id) {
        $tmp[$item_id] = array();
        foreach($pay_range_list as $pay_stamp=>$pay_value) {
          if($pay_stamp<=$stamp) {     
            $tmp[$item_id][$pay_stamp] = $pay_value;
            unset($pay_range_list[$pay_stamp]);
          }
        }
        if(count($tmp[$item_id])==0)
          unset($tmp[$item_id]);
      }
      $result[$pid][$user] = $tmp;
    }
  }
  $SQL = "";
  foreach($result as $pid=>&$users){
    foreach($users as $ext_id=>&$items) {
      $SQL = "INSERT INTO log_payBetweenTasks (`project_id`,`ext_id`,`reg_time`,`stamp`,`pay`,`quest_id`) VALUES ";
      foreach($items as $item=>&$stamps)
        foreach($stamps as $stamp=>&$value) 
          $SQL .= "($pid,$ext_id,0,$stamp,$value,$item),";
      $SQL = substr($SQL,0,-1).";";
      $db_answer = $db->query($SQL);
      var_dump($db_answer);
      if(!$db_answer) {
       echo "SQL: $SQL\r\n";      
        echo "Error: ".$db->error."\r\n\r\n\r\n";
      }
    }
  }

  $SQL = "SELECT reg_time,ext_id,project_id FROM log_NewPlayer where ext_id in (select ext_id from log_payBetweenTasks WHERE reg_time = 0);";
  $db_answer = $db->query($SQL);
  var_dump($db_answer);
  if(!$db_answer) {
    echo "Error: ".$db->error."\r\n\r\n\r\n";
  }

  $result = array();
  while($row=$db_answer->fetch_assoc()) {
    $ext_id = $row['ext_id'];
    $reg_time = $row['reg_time'];
    $project_id = $row['project_id'];
    $result[$project_id.$ext_id] = array($ext_id,$reg_time,$project_id);
  }

  foreach($result as $item){
    $SQL = "UPDATE log_payBetweenTasks SET reg_time=".$item[1]." WHERE project_id=".$item[2]." and ext_id=\"".$item[0]."\";";
    $db_answer = $db->query($SQL);
    var_dump($db_answer);
    if(!$db_answer) {
      echo "SQL: $SQL\r\n";      
      echo "Error: ".$db->error."\r\n\r\n\r\n";
    }
  }
}
