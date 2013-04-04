<?php

if(PHP_SAPI != 'cli')
{
  header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
  header('Expires: Mon, 27 Sep 2010 14:48:52 +0400');
  header('Pragma: no-cache');

  if(array_key_exists('REDIRECT_LIMB_URI', $_SERVER))
  {
    $_SERVER['REQUEST_URI'] = dirname($_SERVER['SCRIPT_NAME']).'/'.$_SERVER['REDIRECT_LIMB_URI'];
    $_SERVER['REDIRECT_URL'] = $_SERVER['REQUEST_URI'];
  }

  // это для кеширование на стороне nginx. минут десять вспоминал...
  if(array_key_exists('HIDE_COOKIE', $_SERVER) && $_SERVER['HIDE_COOKIE'])
  {
    $_ENV["HTTP_COOKIE"] = '';
    $_SERVER["HTTP_COOKIE"] = '';
    foreach($_COOKIE as $name => $value)
      unset($_REQUEST[$name]);
    $_COOKIE = array();
  }
}

require_once('limb/core/common.inc.php');
require_once('limb/web_app/common.inc.php');
require_once('limb/i18n/common.inc.php');
require_once('limb/i18n/src/charset/driver.inc.php');
require_once('limb/cache2/common.inc.php');

require_once(dirname(__FILE__).'/common.inc.php');

// toolkit
lmbToolkit :: merge(new spTools());
lmbToolkit :: instance()->setSupportedViewTypes(array('.phtml' => 'lmbMacroView'));
lmbToolkit :: instance()->getResponse()->setRedirectStrategy(new RelativeRedirectStrategy());

lmb_use_charset_driver(new lmbUTF8MbstringDriver());

function is_dev_mode()
{
  return lmb_env_get('LIMB_APP_MODE') == 'devel';  
}

lmb_env_set('LIMB_CACHE_DB_META_IN_FILE', !is_dev_mode());
ini_set('display_errors', is_dev_mode());
