<?php

require_once(__DIR__.'/../setup.php');

main(time());
exit(0);

function main($time) {
  $host = lmb_env_get('DB_HOST');
  $user = lmb_env_get('DB_USER');
  $pass = lmb_env_get('DB_PASS');
  $time_start  = round($time/86400)-1;
  $time_stop   = round($time/86400);
  $time_start *= 86400;
  $time_stop  *= 86400;
  $db = new mysqli($host,$user,$pass,'stats2');
  
  $tables_db = $db->query("SELECT * FROM log_Pay WHERE stamp>=$time_start and stamp<=$time_stop");
  $result = array();
  while($row = $tables_db->fetch_assoc()) {    
      $tables[] = $row[0];
  }
  print_r($tables);  
}
