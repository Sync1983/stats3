<?php

require_once(__DIR__.'/../setup.php');

$host = lmb_env_get('DB_HOST');
$user = lmb_env_get('DB_USER');
$pass = lmb_env_get('DB_PASS');
          
main();
exit(0);

function main() {
  $host = lmb_env_get('DB_HOST');
  $user = lmb_env_get('DB_USER');
  $pass = lmb_env_get('DB_PASS');
  $depricate = array('log_Pay','log_NewPlayer','logger','logger_chart','log_MAU');

  $db = new mysqli($host,$user,$pass,'stats2');
  $tables_db = $db->query("SHOW TABLES LIKE 'log_%'");
  $tables = array();
  while($row = $tables_db->fetch_array()) {
    if(!in_array($row[0],$depricate))
      $tables[] = $row[0];
  }
  print_r($tables);
  $time = time() - 45*86400;
  foreach($tables as $table) {
    $SQL = "DELETE FROM $table where stamp<$time";
    echo "SQL: $SQL\r\n";
    $result = $db->query($SQL);
    var_dump($result);
  }
}
