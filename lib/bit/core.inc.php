<?php

function bit_conf($key = null, $safe = false)
{
  static $conf = null;
  if(null === $conf)
    $conf = new stdClass;
  if(null === $key)
    return $conf;
  if(isset($conf->$key))
    return $conf->$key;
  if($safe)
    return null;
  throw new Exception('Conf "'.$key.'" not set!');
}

function bit_lazy_class($class, $path)
{
  static $conf = null;
  if(null === $conf)
  {
    $conf = bit_conf();
    if(!isset($conf->lazy_class_paths))
      $conf->lazy_class_paths = array();
  }
  $conf->lazy_class_paths[$class] = $path;
}

function bit_autoload($class)
{
  $conf = bit_conf();
  if(isset($conf->lazy_class_paths[$class]))
    require_once($conf->lazy_class_paths[$class]);
}

function bit_log_write($message, $log_name = 'base')
{
  $message = @date("Y-m-d H:i:s", time()) . " " . strtr($message, array("\n" => "\t")) . "\n";
  file_put_contents(bit_conf('var_dir') . '/'.$log_name.'.log', $message, FILE_APPEND);
}

function bit_exception_wrap($e, $message)
{
  return bit_error_print($message . ' ' . $e->getMessage() . ', file '.$e->getFile().', line '.$e->getLine());  
}    

function bit_exception_guard($e)
{
  bit_error_guard($e);  
  exit(1);
}

function bit_error_guard($errno = 0, $errstr = '', $errfile = null, $errline = null, $errcontext = array())
{
  if($errno instanceof Exception)
  {
    $exp = $errno;
    $errno = $exp->getCode();
    $errfile = $exp->getFile();
    $errline = $exp->getLine();
    $errstr = $exp->getMessage();
  }
  elseif(!error_reporting())
    return;
  $message = '';
  if($errstr)
    $message .= $errstr;
  if($errno)
    $message .= ', error no: '.$errno;
  if($errfile)
    $message .= ', file '.$errfile;
  if($errline)
    $message .= ' line '.$errline;
  bit_error_print($message);
}

function bit_error_print($message)
{
  bit_log_write($message);
  if(($callback = bit_conf('print_error_callback', true)) && is_callable($callback))
  {
    call_user_func_array($callback, array($message));    
    return;
  }
  if(bit_conf('is_debug_mode', true))
    echo "[ERR] " . $message . "\n";
}

function bit_profile($name = null)
{
  static $time, $last_name, $mem;
  if($name)
  {
    $time = microtime(1);
    $mem = memory_get_usage();
    $last_name = $name;
  }
  else
    printf("%s: %.5fs %.1fM %dM\n", $last_name, microtime(1) - $time, (memory_get_usage() - $mem) / 1048576, memory_get_peak_usage() / 1048576);
}

function bit_profile_log($name_or_id = null, $debug = null)
{
  static $times = array(), $id_counter = 0; 
  if(is_string($name_or_id))
  {
    $id = ++$id_counter;
    $times[$id] = array($name_or_id, microtime(1), memory_get_usage());
    return $id;
  }
  else
  {
    if(isset($times[$name_or_id]))
    {
      list($last_name, $time, $mem) = $times[$name_or_id];
      $process_time = microtime(1) - $time;
      if($process_time < (0 + bit_conf('min_profile_logging_time', true)))
        return;
      bit_log_write(
        sprintf(
          "%s: %.5fs %.1fM %dM %s\n", 
          $last_name, 
          $process_time, 
          (memory_get_usage() - $mem) / 1048576, 
          memory_get_peak_usage() / 1048576,
          $debug ?: ''
        ), 
        'profile');
    }
  }
}

function bit_error_shutdown_handler()
{
  $error = error_get_last();
  if($error && ($error['type'] & (E_ERROR | E_COMPILE_ERROR)))
    bit_error_guard($error['type'], $error['message'], $error['file'], $error['line']);
}

function bit_serialize($data)
{
  if(bit_conf('use_igbinary', true))
    return igbinary_serialize($data);
  return serialize($data);  
}

function bit_unserialize($data)
{
  if(bit_conf('use_igbinary', true))
    return igbinary_unserialize($data);
  return unserialize($data);
}

set_error_handler('bit_error_guard');
set_exception_handler('bit_exception_guard');
spl_autoload_register('bit_autoload');
register_shutdown_function('bit_error_shutdown_handler');
