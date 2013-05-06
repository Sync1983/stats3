<?php

lmb_require('limb/fs/src/lmbFs.class.php');

class SimpleLogOneFileWriter implements lmbLogWriter
{
  protected $log_files;

  function __construct(lmbUri $uri)
  {
    $this->log_file = $uri->getPath();
  }

  function write(lmbLogEntry $entry)
  {
    $this->_appendToFile($this->log_file,
                         str_replace("\n", "\t", $entry->asText()),
                         $entry->getTime());
  }

  protected function _appendToFile($file_name, $message, $stamp)
  {
    lmbFs :: mkdir(dirname($file_name), 0775);
    $file_existed = file_exists($file_name);
    @file_put_contents($file_name, sprintf("[%s] %s\n", date("Y-m-d H:i:s", $stamp), $message), FILE_APPEND | LOCK_EX);
    if(!$file_existed)
      @chmod($file_name, 0664);
  }
}


