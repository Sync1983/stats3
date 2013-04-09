<?php

require_once(dirname(__FILE__) . '/../setup.php');
$PROJECT_DIR = dirname(__FILE__) . '/../';
lmb_require('limb/fs/src/lmbFs.class.php');

system('php '.escapeshellarg($PROJECT_DIR).'cli/mysql_migrate.php');

$var_dir = lmb_env_get('LIMB_VAR_DIR');

system('mkdir -p '.escapeshellarg($var_dir));
system('chmod 777 '.escapeshellarg($var_dir));

system('rm -rf ' . escapeshellarg($var_dir) . '/db_info.*');
lmbFs :: rm($var_dir . '/locators');
lmbFs :: rm($var_dir . '/i18n-qt');
lmbFs :: rm($var_dir . '/compiled');

$skell_dir = realpath(dirname(__FILE__) . '/../../share_php/lib/frontend_skel/www_skel/');

$www_dir = realpath(dirname(__FILE__) . '/../www/');
system(sprintf('rm -f %s', escapeshellarg($www_dir.'/shared')));
system(sprintf('ln -vs %s %s', escapeshellarg($skell_dir.'/shared'), escapeshellarg($www_dir.'/shared')));
 
