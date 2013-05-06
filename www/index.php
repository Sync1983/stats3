<?php

require_once(dirname(__FILE__) . '/../setup.php');
require_once('src/AdminApplication.class.php');

$application = new AdminApplication();
$application->process();
