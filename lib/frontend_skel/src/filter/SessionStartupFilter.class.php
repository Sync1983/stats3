<?php

lmb_require_class('limb/web_app/src/filter/lmbSessionStartupFilter.class.php', 'lmbSessionStartupFilter');

class SessionStartupFilter extends lmbSessionStartupFilter
{
  function run($filter_chain)
  {
    $dir = lmb_env_get('LIMB_VAR_DIR') . '/sessions/';
    if(!file_exists($dir))
      lmbFs :: mkdir($dir);
    session_save_path($dir);
    parent :: run($filter_chain, false);
  }
}
