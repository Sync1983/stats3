<?php

class RelativeRedirectStrategy
{
  function redirect($response, $path, $code = null)
  {
    if(is_null($code))
      $code = 302;
    if(strpos($path, 'http://') === 0)
    {
      $response->header("Location: {$path}", true, $code);
      return;
    }
    $response->header("Location: ". LIMB_HTTP_BASE_PATH . ltrim($path, '/'), true, $code);
  }
}
