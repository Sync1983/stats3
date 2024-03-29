<?php

function mysql_connect_string($host, $user, $password)
{
  $password = ($password)? '-p' . $password : '';
  return "mysql -h$host -u$user $password";
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

//  if($ret)
//    throw new Exception("Shell command '$cmd' executing error \n");// . implode("\n", $out));
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
  $password = ($password)? '-p' . $password : '';
  $cmd = "mysql -NB -u$user $password -h$host -e\"SHOW TABLES\" $database";
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
  $password = ($password)? '-p' . $password : '';
  $cmd = "mysqldump -u$user $password -h$host " .
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
  $password = ($password)? '-p' . $password : '';
  $cmd = "mysqldump -u$user $password -h$host " .
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

  $password = ($password)? '-p' . $password : '';
  $cmd = "mysql -u$user $password -h$host --default-character-set=$charset $database < $file";

  echo "Starting loading '$file' file to '$database' DB...";

  system($cmd, $ret);

  if(!$ret)
    echo "done! (" . filesize($file) . " bytes)\n";
  else
    echo "error!\n";
}

function mysql_copy_schema($host_src, $user_src, $password_src, $database_src,
                           $host_dst, $user_dst, $password_dst, $database_dst)
{
  $tables = mysql_get_tables($host_src, $user_src, $password_src, $database_src);

  $password_src = ($password_src)? '-p' . $password_src : '';
  $password_dst = ($password_dst)? '-p' . $password_dst : '';

  echo "Starting cloning schema from '$database_src' DB to '$database_dst' DB...\n";

  foreach($tables as $table)
  {
    $cmd = "mysql -NB -u$user_src $password_src -h$host_src -e\"SHOW CREATE TABLE $table\" $database_src";
    list(,$create_schema) = explode("\t", `$cmd`, 2);

    $create_schema = str_replace('\n', '', escapeshellarg(trim($create_schema)));
    $cmd = "mysql -u$user_dst $password_dst -h$host_dst -e$create_schema $database_dst";
    system($cmd, $ret);
    if(!$ret)
      echo "'$table' copied\n";
    else
      echo "error while copying '$table'\n";
  }
  echo "done\n";
}

function mysql_copy_schema_and_use_memory_engine($host_src, $user_src, $password_src, $database_src,
                           $host_dst, $user_dst, $password_dst, $database_dst)
{
  $tables = mysql_get_tables($host_src, $user_src, $password_src, $database_src);

  $password_src = ($password_src)? '-p' . $password_src : '';
  $password_dst = ($password_dst)? '-p' . $password_dst : '';

  echo "Starting cloning schema from '$database_src' DB to '$database_dst' DB...\n";

  foreach($tables as $table)
  {
    $cmd = "mysql -NB -u$user_src $password_src -h$host_src -e\"SHOW CREATE TABLE $table\" $database_src";
    list(,$create_schema) = explode("\t", `$cmd`, 2);

    $create_schema = str_replace('\n', '', escapeshellarg(trim($create_schema)));
    $create_schema = preg_replace('/(.*)ENGINE=([^\s]*)(.*)/', '${1}ENGINE=MEMORY${3}', $create_schema);

    $create_schema = str_replace(array(' longtext', ' blob', ' text'), ' varchar(255)', $create_schema);

    $cmd = "mysql -u$user_dst $password_dst -h$host_dst -e$create_schema $database_dst";
    system($cmd, $ret);
    if(!$ret)
      echo "'$table' copied\n";
    else
      echo "error while copying '$table'\n";
  }
  echo "done\n";
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
  $password = ($password)? '-p' . $password : '';
  foreach($tables as $table)
  {
    $cmd = "mysql -u$user $password -h$host -e\"DROP TABLE $table\" $database";
    system($cmd, $ret);
    if(!$ret)
      echo "'$table' removed\n";
    else
      echo "error while removing '$table'\n";
  }
}

function mysql_truncate_tables($host, $user, $password, $database, $tables)
{
  $password = ($password)? '-p' . $password : '';
  foreach($tables as $table)
  {
    $cmd = "mysql -u$user $password -h$host -e\"TRUNCATE TABLE $table\" $database";
    system($cmd, $ret);
    if(!$ret)
      echo "'$table' truncated\n";
    else
      echo "error while truncating '$table'\n";
  }
}

function get_migration_files_since($base_version)
{
  $files = array();
  $migrations_dir = dirname(__FILE__) . '/../init/migrate/';
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
  $migrations_dir = dirname(__FILE__) . '/../init/migrate/';
  $files = glob($migrations_dir . '*');
  krsort($files);
  return reset($files);
}

function mysql_migrate($host, $user, $password, $database, $since = null)
{
  if(!mysql_db_exists($host, $user, $password, $database))
    return;

  if(!mysql_table_exists($host, $user, $password, $database, 'schema_info'))
    mysql_exec($host, $user, $password, $database, 'CREATE TABLE schema_info ("version" integer default 0);');

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
  $sql_schema = dirname(__FILE__) . '/../init/schema.mysql';
  $sql_data = dirname(__FILE__) . '/../init/data.mysql';

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

function mysql_copy_tables_contents($host_src, $user_src, $password_src, $database_src,
                                    $host_dst, $user_dst, $password_dst, $database_dst,
                                    $tables)
{
  echo "\nStarting copying tables contents from 'photosight' DB to 'photosight_tests' DB...\n";

  $cmd = mysql_connect($host_src, $user_src, $password_src);

  mysql_query ("set character_set_client='utf8'", $cmd);
  mysql_query ("set character_set_results='utf8'", $cmd);
  mysql_query ("set collation_connection='utf8_general_ci'", $cmd);

  mysql_select_db($database_src, $cmd);

  $dump = array();
  foreach($tables as $table)
  {
    $sql = "SELECT * FROM " . $table . ";";
    $result = mysql_query($sql, $cmd);
    while($record = mysql_fetch_assoc($result))
    {
      $dump[$table][] = $record;
    }
  }

  mysql_close($cmd);


  $cmd = mysql_connect($host_dst, $user_dst, $password_dst);

  mysql_query ("set character_set_client='utf8'", $cmd);
  mysql_query ("set character_set_results='utf8'", $cmd);
  mysql_query ("set collation_connection='utf8_general_ci'", $cmd);

  mysql_select_db($database_dst, $cmd);

  foreach($dump as $table => $records)
  {
    $sql = "INSERT INTO " . $table . " VALUES (";
    foreach($records as $record)
    {
      foreach($record as $field)
        $sql .= "'" . substr($field, 0, 255) . "',";

      $sql = preg_replace('/,$/', '', $sql);
      $sql .= "),(";
    }
    $sql = preg_replace('/,\($/', ';', $sql);

    if(mysql_query($sql, $cmd))
      echo "'" . $table . "' copied content\n";
  }

  mysql_close($cmd);

  echo "done\n";
}
