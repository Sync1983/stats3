<?php

include_once('limb/view/settings/macro.conf.php');

$conf['cache_dir'] = lmb_env_get('LIMB_VAR_DIR') . '/compiled/';
$conf['is_force_compile'] = false;
$conf['is_force_scan'] = false;
$conf['tpl_scan_dirs'] = lmb_env_get('LIMB_TEMPLATES_INCLUDE_PATH', array('template'));
