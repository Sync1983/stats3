<?php

class DebugController extends spController {
  
  function doDisplay() {
    $nginx = "";
    $fp = fopen('/var/log/stats2/nginx-data.error.log',"r");
    if(!$fp)
      $nginx = "Error\r\n";
    while (!feof($fp))
      $nginx .= fgets ($fp);
    fclose($fp);

    $combine = "";
    $fp = fopen('/home/stats2/stats2/admin/var/log/combine.log',"r");
    while (!feof($fp))
      $combine .= fgets ($fp);
    fclose($fp);
    
    $this->view->set('nginx_error',$nginx);
    $this->view->set('combine_error',$combine);    
  }
  
}
