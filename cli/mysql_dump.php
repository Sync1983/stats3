<?php

define('BASE_GAMEPORTAL_HOST', 'zveriki.com');

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
mysql_dump_schema($host, $user, $password, $database, $charset, $sql_schema);
mysql_dump_data($host, $user, $password, $database, $charset, $sql_data);

?>
