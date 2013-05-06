<?php

$classes =& $_ENV['LIMB_LAZY_CLASS_PATHS'];

$classes['spController'] = 'src/spController.class.php';
$classes['ARSingleton'] = 'src/model/ARSingleton.class.php';
$classes['spAdminObjectController'] = 'src/spAdminObjectController.class.php';
$classes['lmbController'] = 'limb/web_app/src/controller/lmbController.class.php';
$classes['lmbUTF8MbstringDriver'] = 'limb/i18n/src/charset/lmbUTF8MbstringDriver.class.php';
$classes['SimpleLog'] = 'src/util/SimpleLog.class.php';
$classes['SimpleLogOneFileWriter'] = 'src/util/SimpleLogOneFileWriter.class.php';
$classes['lmbDBAl'] = 'limb/dbal/src/lmbDBAL.class.php';

// toolkit
require_once(dirname(__FILE__) . '/src/spTools.class.php');
require_once(dirname(__FILE__) . '/src/util/RelativeRedirectStrategy.class.php');

