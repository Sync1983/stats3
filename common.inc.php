<?php

$classes =& $_ENV['LIMB_LAZY_CLASS_PATHS'];

$classes['Member'] = 'src/model/Member.class.php';
$classes['Project'] = 'src/model/Project.class.php';
$classes['PageView'] = 'src/model/PageView.class.php';
$classes['Pager'] = 'src/model/Pager.class.php';
/*$classes['Counter'] = 'src/model/Counter.class.php';
$classes['Referrer'] = 'src/model/Referrer.class.php';
$classes['Chart'] = 'src/model/Chart.class.php';
$classes['Counter2'] = 'src/model/Counter2.class.php';
$classes['Preset'] = 'src/model/Preset.class.php';
$classes['Sumview'] = 'src/model/Sumview.class.php';
$classes['Logger'] = 'src/model/Logger.class.php';
$classes['loggerEvent']             = 'src/model/loggerEvent.class.php';
$classes['log_addStock']            = 'src/model/log_clases.php';
$classes['log_costStock']           = 'src/model/log_clases.php'; 
$classes['log_FeatureUse']          = 'src/model/log_clases.php';
$classes['log_LevelUp']             = 'src/model/log_clases.php';
$classes['log_Login']               = 'src/model/log_clases.php';
$classes['log_NewPlayer']           = 'src/model/log_clases.php';
$classes['log_OutEnergy']           = 'src/model/log_clases.php';
$classes['log_payCost']             = 'src/model/log_clases.php';
$classes['log_QuestDone']           = 'src/model/log_clases.php';
$classes['log_QuestStart']          = 'src/model/log_clases.php';
$classes['log_QuestTaskComplete']   = 'src/model/log_clases.php';
$classes['log_viralRecive']         = 'src/model/log_clases.php';
$classes['log_viralSend']           = 'src/model/log_clases.php';*/

// toolkit
require_once(__DIR__ . '/src/spLoginTools.class.php');
//require_once(__DIR__ . '/src/StatsTools.class.php');
//require_once(__DIR__ . '/src/StatsMath.class.php');
//require_once(__DIR__ . '/src/redis.inc.php');
//require_once(__DIR__ . '/src/sqlHashHelper.inc.php');

//require_once('bit/stats/common.inc.php');


