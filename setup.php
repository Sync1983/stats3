<?php

set_include_path(dirname(__FILE__) . PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/frontend_skel/' . PATH_SEPARATOR .
                 dirname(__FILE__) . '/lib/' . PATH_SEPARATOR . 
                 dirname(__FILE__) . '/web/' . PATH_SEPARATOR
                 );

require_once('limb/core/common.inc.php');

if(file_exists(dirname(__FILE__) . '/setup.override.php'))
  require_once(dirname(__FILE__) . '/setup.override.php');

date_default_timezone_set('Etc/GMT-3');
require_once('skel_setup.php');

// vars
lmb_env_setor('stats_projects_dir', dirname(__FILE__). '/var/stats/');
lmb_env_setor('stats_access_log',   dirname(__FILE__). '/var/stats_access.log');
lmb_env_setor('LIMB_VAR_DIR',       dirname(__FILE__). '/var/');
lmb_env_set('LIMB_DOCUMENT_ROOT',   dirname(__FILE__).'/www/');

require_once('common.inc.php');
require_once('setup.override.php');

// toolkit
lmbToolkit :: merge(new spLoginTools());
lmbToolkit :: merge(new spDataTools());