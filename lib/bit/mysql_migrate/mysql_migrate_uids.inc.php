<?php


function bit_mysql_uid_migrate($dbal_name, $dir)
{
  $conn = dbal_by_name($dbal_name);
  if(!$conn->fetchOneValue('SHOW TABLES LIKE \'schema_migrates\''))
    $conn->execute('CREATE TABLE schema_migrates (migration varchar(255) default \'\', PRIMARY KEY (migration)) ENGINE=InnoDB;');

  $migrations = array();
  foreach(glob($dir . '/*') as $file)
  {
    if(!is_file($file))
      continue;
    $migrations[] = pathinfo($file, PATHINFO_FILENAME);
  }
  $migrations = array_unique($migrations);
  sort($migrations);

  foreach($migrations as $migrate)
  {
    $sql = $dir . '/' . $migrate . '.sql';
    if(file_exists($sql) && is_file($sql))
    {
      $file = basename($sql);
      if(0 == $conn->fetchOneValue('SELECT COUNT(*) FROM schema_migrates WHERE migration=\''.$conn->escape($file).'\''))
      {
        list($host, $user, $password, $database, $charset) = bit_mysql_conf($dbal_name);
        mysql_load($host, $user, $password, $database, realpath($sql));
        $conn->execute('INSERT INTO schema_migrates (migration) VALUES(\''.$conn->escape($file).'\')');
      }
    }
    $php = $dir . '/' . $migrate . '.php';
    if(file_exists($php) && is_file($php))
    {
      require_once($php);
      $func = 'migrate_' . basename($php, '.php');
      if(!function_exists($func))
      {
        echo "ERROR: function {$func} not exists!\n";
        break;
      }
      $file = basename($php);
      if(0 == $conn->fetchOneValue('SELECT COUNT(*) FROM schema_migrates WHERE migration=\''.$conn->escape($file).'\''))
      {
        call_user_func_array($func, array($conn));
        $conn->execute('INSERT INTO schema_migrates (migration) VALUES(\''.$conn->escape($file).'\')');
      }
    }
  }
}

function bit_mysql_load_by_conn($conn, $sql)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($conn);
  mysql_load($host, $user, $password, $database, realpath($sql));
}
