<?php

$_ENV['BIT_MYSQL_MIGRATES_DIR'] = null;
require_once(__DIR__ . '/cConnection.class.php');

function bit_mysql_cname($connection_name)
{
  if(is_string($connection_name))  
    return $connection_name;
  return get_class($connection_name);
}

function bit_mysql_conf($connection_name)
{
  if(is_object($connection_name) && get_class($connection_name) == 'lmbDbDSN')
  {
    $conf = array(
      'host' => $connection_name->getHost(),
      'port' => $connection_name->getPort(),
      'user' => $connection_name->getUser(),
      'password' => $connection_name->getPassword(),
      'database' => $connection_name->getDatabase(),
    );
  }
  elseif(is_object($connection_name) && $connection_name instanceof DbalConnection)
    $conf = $connection_name->getConf();
  else
    $conf = bit_conf('db_'.$connection_name);

  $host = $conf['host'];
  $user = $conf['user'];
  $password = $conf['password'];
  $database = $conf['database'];
  $charset = 'utf8';

  if(isset($conf['port']))
    $host .= ':'.$conf['port'];
  return array($host, $user, $password, $database, $charset);
}

function bit_mysql_load($connection_name, $base_dir)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($connection_name);
  $_ENV['BIT_MYSQL_MIGRATES_DIR'] = $base_dir;

  $sql_schema = $base_dir . '/schema.mysql';
  $sql_data = $base_dir . '/data.mysql';

  mysql_db_cleanup($host, $user, $password, $database);
  mysql_dump_load($host, $user, $password, $database, $charset, $sql_schema);
  mysql_dump_load($host, $user, $password, $database, $charset, $sql_data);
}

function bit_mysql_load_file($connection_name, $file)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($connection_name);
  mysql_dump_load($host, $user, $password, $database, $charset, $file);
}

function bit_mysql_dump($connection_name, $base_dir)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($connection_name);
  $_ENV['BIT_MYSQL_MIGRATES_DIR'] = $base_dir;

  $sql_schema = $base_dir . '/schema.mysql';
  $sql_data = $base_dir . '/data.mysql';

  mysql_dump_schema($host, $user, $password, $database, $charset, $sql_schema);
  mysql_dump_data($host, $user, $password, $database, $charset, $sql_data);
}

function bit_mysql_migrate($connection_name, $base_dir)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($connection_name);
  $_ENV['BIT_MYSQL_MIGRATES_DIR'] = $base_dir;
  
  echo "===== Migrating production DB ".bit_mysql_cname($connection_name)." =====\n";
  mysql_migrate($host, $user, $password, $database, null);
}

function bit_mysql_diff($connection_name, $base_dir, $umigrates = null)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($connection_name);
  $base_dir = $_ENV['BIT_MYSQL_MIGRATES_DIR'] = realpath($base_dir) . '/';

  $schema = $base_dir . '/schema.mysql';
  $data = $base_dir . '/data.mysql';

  if(preg_match('~INSERT\s+INTO\s+.*schema_info\D+(\d+)~i', file_get_contents($data), $m))
    $since = $m[1];
  else
    $since = -1;

  //collecting all not applied migrations
  $migrations = array();
  foreach(glob($base_dir . '/migrate/*.sql') as $migration)
  {
    list($version,) = explode('_', basename($migration));
    if($since < intval($version))
      $migrations[] = $migration;
  }
  asort($migrations);

  if($umigrates && is_dir($umigrates))
  {
    foreach(glob($umigrates . '/*.sql') as $migration)
      $migrations[] = $migration;
  }

  $working_db = array(
    'hostname' => $host,
    'username' => $user,
    'password' => $password,
    'database' => $database
  );

  $conn = new cConnection($host, $user, $password);
  $conn->open();
  $tmp_db = $conn->createTemporaryDatabase();

  $repos_db = $working_db;
  $repos_db['database'] = $tmp_db;

  $conn->importSql($tmp_db, $schema);

  foreach($migrations as $migration)
    $conn->importSql($tmp_db, $migration);

  echo generateScript($repos_db, $working_db);

  $conn->dropDatabase($tmp_db);
  $conn->close();
}

function bit_mysql_create_migration($connection_name, $base_dir, $name)
{
  list($host, $user, $password, $database, $charset) = bit_mysql_conf($connection_name);
  $_ENV['BIT_MYSQL_MIGRATES_DIR'] = $base_dir;

  if(!$name)
  {
    echo "Specify migration name\n";
    exit(1);
  }

  echo "===== Migrating production DB to apply all migrations =====\n";
  bit_mysql_migrate($connection_name, $base_dir);

  ob_start();
  bit_mysql_diff($connection_name, $base_dir);
  $diff = ob_get_flush();

  if($diff)
  {
    $last = get_last_migration_file();
    if($last && file_get_contents($last) == $diff)
    {
      echo "The last migration file '$last' is identical to the new migration, skipped\n";
      exit();
    }

    $stamp = time();
    $file = $base_dir . "/migrate/{$stamp}_{$name}.sql";

    echo "Writing new migration to file '$file'...";
    file_put_contents($file, $diff);
    echo "done! (" . strlen($diff). " bytes)\n";

    if(!mysql_test_migration($host, $user, $password))
      echo "\nWARNING: migration has errors, please correct them before committing! Try dry-running it with mysql_migrate.php --dry-run\n";

    echo "Updating version info...";
    mysql_exec($host, $user, $password, $database, "UPDATE schema_info SET version = $stamp;");
    echo "done!\n";
  }
  else
    echo "There haven't been any changes according to the latest dump\n";
}

/////////////////////////////////////////////////////////////

function mysql_connect_string($host, $user, $password, $cmd = 'mysql')
{
  $exp = explode(':', $host, 2);
  $port = 0;
  if(count($exp) == 2)
    list($host, $port) = $exp;
  else
    list($host) = $exp;

  if(!$port)
    $port = 3306;
    
  $password = ($password)? '-p' . $password : '';
  return "$cmd -h$host -u$user $password -P$port ";
}

function mysql_exec($host, $user, $password, $database, $cmd)
{
  $shell_cmd = mysql_connect_string($host, $user, $password) . ' -e"' . $cmd . '" -N -B ' . $database . ' 2>&1';
  exec($shell_cmd, $out, $ret);
  $outstr = trim(implode("\n", $out));

  if($ret)
    throw new Exception("Shell command '$shell_cmd' executing error \n'$outstr'");

  if(preg_match('~ERROR\s+\d+\s+\(\d+\)~', $outstr))
    throw new Exception("MySQL command '$cmd' with error \n'$outstr'");

  return $outstr;
}

function mysql_create_database_if_not_exists($host, $user, $password, $database)
{
  $cmd = mysql_connect_string($host, $user, $password) . " -e 'CREATE DATABASE IF NOT EXISTS $database'";
  exec($cmd, $out, $ret);
  echo trim(implode("\n", $out));

  if($ret)
    throw new Exception("Shell command '$cmd' executing error \n" . implode("\n", $out));
}

function mysql_load($host, $user, $password, $database, $file)
{         
  mysql_create_database_if_not_exists($host, $user, $password, $database);
  $cmd = mysql_connect_string($host, $user, $password) . " $database < $file 2>&1";

  echo "Starting loading '$file' file to '$database' DB...";

  exec($cmd, $out, $ret);
  $outstr = trim(implode("\n", $out));

  if($ret)
    throw new Exception("Shell command '$cmd' executing error \n'$outstr'");

  if(preg_match('~ERROR\s+\d+\s+\(\d+\)~', $outstr))
    throw new Exception("MySQL specific error \n'$outstr'");

  echo "done\n";
}

function mysql_db_exists($host, $user, $password, $database)
{
  $res = mysql_exec($host, $user, $password, '', "SHOW DATABASES");
  return strpos($res, $database) !== false;
}

function mysql_table_exists($host, $user, $password, $database, $table)
{
  $res = mysql_exec($host, $user, $password, $database, "SHOW TABLES");
  return strpos($res, $table) !== false;
}

function mysql_get_tables($host, $user, $password, $database)
{
  $cmd = mysql_connect_string($host, $user, $password, "mysql -NB ") . " -e\"SHOW TABLES\" $database";
  $tables = array_filter(explode("\n", `$cmd`));
  return $tables;
}

function mysql_create_tmp_db($host, $user, $password)
{
  $database = "temp_mysql_" . uniqid();
  echo "Creating tmp db '$database'...";
  mysql_exec($host, $user, $password, '', "CREATE DATABASE $database");
  echo "done\n";
  return $database;
}

function mysql_db_drop($host, $user, $password, $database)
{
  mysql_exec($host, $user, $password, $database, "DROP DATABASE $database");
}

function mysql_dump_schema($host, $user, $password, $database, $charset, $file, $tables = array())
{
  $cmd = mysql_connect_string($host, $user, $password, 'mysqldump') . " " .
         "-d --default-character-set=$charset " .
         "--quote-names --allow-keywords --add-drop-table " .
         "--set-charset --result-file=$file " .
         "$database " . implode('', $tables);

  echo "Starting dumping schema to '$file' file...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

function mysql_dump_data($host, $user, $password, $database, $charset, $file, $tables = array())
{
  $cmd = mysql_connect_string($host, $user, $password, 'mysqldump') . " " .
         "-t --default-character-set=$charset " .
         "--add-drop-table --create-options --quick " .
         "--allow-keywords --max_allowed_packet=16M --quote-names " .
         "--complete-insert --set-charset --result-file=$file " .
         "$database " . implode('', $tables);


  echo "Starting dumping to '$file' file...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

function mysql_dump_load($host, $user, $password, $database, $charset, $file)
{
  mysql_create_database_if_not_exists($host, $user, $password, $database);

  $cmd = mysql_connect_string($host, $user, $password) . " --default-character-set=$charset $database < $file";

  echo "Starting loading '$file' file to '$database' DB...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

function mysql_db_cleanup($host, $user, $password, $database)
{
  $tables = mysql_get_tables($host, $user, $password, $database);
  mysql_drop_tables($host, $user, $password, $database, $tables);

  echo "Starting cleaning up '$database' DB...\n";

  echo "done\n";
}

function mysql_drop_tables($host, $user, $password, $database, $tables)
{
  foreach($tables as $table)
  {
    $cmd = mysql_connect_string($host, $user, $password) . " -e\"DROP TABLE $table\" $database";
    system($cmd, $ret);
    if(!$ret)
      echo "'$table' removed\n";
    else
      echo "error while removing '$table'\n";
  }
}

function get_migration_files_since($base_version)
{
  $files = array();
  $migrations_dir = $_ENV['BIT_MYSQL_MIGRATES_DIR'].'/migrate/';
  foreach(glob($migrations_dir . '*') as $file)
  {
    list($version, ) = explode('_', basename($file));
    $version = intval($version);
    if($version > $base_version)
      $files[$version] = $file;
  }
  ksort($files);
  return $files;
}

function get_last_migration_file()
{
  $migrations_dir = $_ENV['BIT_MYSQL_MIGRATES_DIR'] . '/migrate/';
  $files = glob($migrations_dir . '*');
  krsort($files);
  return reset($files);
}

function mysql_migrate($host, $user, $password, $database, $since = null)
{
  if(!mysql_db_exists($host, $user, $password, $database))
    return;

  if(!mysql_table_exists($host, $user, $password, $database, 'schema_info'))
    mysql_exec($host, $user, $password, $database, 'CREATE TABLE schema_info ("version" integer default 0) ENGINE=InnoDB;');

  if(!mysql_exec($host, $user, $password, $database, 'SELECT COUNT(*) as c FROM schema_info'))
    mysql_exec($host, $user, $password, $database, 'INSERT INTO schema_info VALUES (' . (int)$since . ');');

  if(is_null($since))
    $since = (int)mysql_exec($host, $user, $password, $database, 'SELECT version FROM schema_info');

  foreach(get_migration_files_since($since) as $version => $file)
  {
    mysql_load($host, $user, $password, $database, $file);
    mysql_exec($host, $user, $password, $database, "UPDATE schema_info SET version=$version;");
  }
}

function mysql_get_schema_version($host, $user, $password, $database)
{
  if(!mysql_table_exists($host, $user, $password, $database, 'schema_info'))
    return null;

  return (int)mysql_exec($host, $user, $password, $database, 'SELECT version FROM schema_info');
}

function mysql_test_migration($host, $user, $password)
{
  echo "Testing migration...\n";

  $tmp_db = mysql_create_tmp_db($host, $user, $password); 
  $sql_schema = $_ENV['BIT_MYSQL_MIGRATES_DIR'] . '/schema.mysql';
  $sql_data = $_ENV['BIT_MYSQL_MIGRATES_DIR'] . '/data.mysql';

  mysql_load($host, $user, $password, $tmp_db, $sql_schema);
  mysql_load($host, $user, $password, $tmp_db, $sql_data);

  try
  {
    mysql_migrate($host, $user, $password, $tmp_db);
  }
  catch(Exception $e)
  {
    echo "\nCaught exception:\n" . $e->getMessage() . "\n";
    mysql_db_drop($host, $user, $password, $tmp_db);
    return false;
  }
  mysql_db_drop($host, $user, $password, $tmp_db);
  return true;
}
