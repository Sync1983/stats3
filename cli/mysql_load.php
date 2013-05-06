<?php
require_once(dirname(__FILE__) . '/../setup.php');
require_once(dirname(__FILE__) . '/mysql.inc.php');

$dsn = lmbToolkit :: instance()->getDefaultDbDSN();

$host = $dsn->getHost();
$user = $dsn->getUser();
$password = $dsn->getPassword();
$database = $dsn->getDatabase();
$charset = $dsn->getCharset();

$sql_schema = dirname(__FILE__) . '/../init/schema.mysql';
$sql_data = dirname(__FILE__) . '/../init/data.mysql';

mysql_db_cleanup($host, $user, $password, $database);
mysql_dump_load($host, $user, $password, $database, $charset, $sql_schema);
mysql_dump_load($host, $user, $password, $database, $charset, $sql_data);