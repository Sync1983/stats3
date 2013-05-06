<?php

function login_service()
{
  $service = bit_conf('login_service', true);
  if(null === $service)
  {
    require_once(__DIR__  . '/BaseLoginService.class.php');
    $service = new BaseLoginService;
    bit_conf()->login_service = $service;
  }
  return $service;
}

