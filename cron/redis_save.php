<?php
  $fp = fopen("/tmp/redis_write.lock","a");
  if(flock($fp,LOCK_EX))
  {
    require_once(__DIR__.'/../setup.php');
    echo "start syncro\r\n";
    echo "start time: ".date("d-m-Y H:i:s")."\r\n";
    cronWorker();
    flock($fp,LOCK_UN);
  } else
  {
    echo "redis to sql lock\r\n";
  }
    echo "stop time: ".date("d-m-Y H:i:s")."\r\n";
  fclose($fp);
  exit(0);
?>
