<?php

function macro_call_func()
{
  $args = func_get_args();  
  $value = array_shift($args);
  $name = array_shift($args);
  array_unshift($args, $value);
  return call_user_func_array($name, $args);
}

function time_period_macro($time)
{
  $day = (int) ($time / 86400);
  $time = $time % 86400;
  $period = gmdate('H:i:s', $time);
  if(substr($period, 0, 3) == '00:')
    $period = substr($period, 3, 6);
  if($day)
    $period = $day.' день '.$period;
  return $period;
}

function date_to_mdy($date)
{
  static $map;
  if(!$map)
    $map = array(
      lmb_i18n("января"), 
      lmb_i18n("февраля"),  
      lmb_i18n("марта"), 
      lmb_i18n("апреля"), 
      lmb_i18n("мая"), 
      lmb_i18n("июня"), 
      lmb_i18n("июля"), 
      lmb_i18n("августа"), 
      lmb_i18n("сентября"), 
      lmb_i18n("октября"), 
      lmb_i18n("ноября"), 
      lmb_i18n("декабря")
    );
  return str_replace('%', $map[intval(date("m", $date))-1], date(lmb_i18n('d % Y', 'formats'), $date));
}

function date_to_mdyhi($date)
{
  return date_to_mdy($date) . ' ' . date('H:i', $date);
}

function date_to_hi($date)
{
  return date('H:i', $date);
}

function cut_content($value, $url = "", $link_text = "", $class = "")
{
  $tag = '<cut />';
  if(!$url)
    return str_replace($tag, "", $value);
  $position = strpos($value, $tag);
  if($position === false)
    return $value;
  $annnotation = substr($value, 0, $position);
  if(strlen($value) == $position + strlen($tag))
    return $annnotation;
  $class_text = ($class) ?" class=\"$class\"" : '';
  if(!$link_text)
    $link_text = lmb_i18n("читать дальше");
  return $annnotation." <a href=\"$url#cut\" $class_text>$link_text</a>";
}

function sign_number_format($value, $decimals, $dec_point, $thousands_sep)
{
  return ($value > 0 ? '+' : '') . number_format($value, $decimals, $dec_point, $thousands_sep);
}
