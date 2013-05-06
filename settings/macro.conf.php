<?php

include_once('limb/view/settings/macro.conf.php');

$conf['cache_dir'] = lmb_env_get('LIMB_VAR_DIR') . '/compiled/';
$conf['is_force_compile'] = $conf['is_force_scan'] = $conf['forcecompile'] = $conf['forcescan'] = is_dev_mode();
$conf['tpl_scan_dirs'] = lmb_env_get('LIMB_TEMPLATES_INCLUDE_PATH', array('template'));
