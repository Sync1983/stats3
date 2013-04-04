<?php

class StastLogReader
{
  protected $_db;
  protected $_file;
  protected $_inode;
  protected $_max_position;
  protected $_skip_save = 0;

  function __construct(DbaWrapper $db, $file_name)
  {
    $this->_db = $db;
    $this->_file = fopen($file_name, 'r');
    if(!is_resource($this->_file))
      throw new Exception('Failed open log file ' . $file_name);
    $stat = fstat($this->_file);
    $this->_max_position = $stat['size'];
    $this->_inode = $stat['ino'];
    $position = 0;
    if($this->_db->has($this->_inode))
    {
      $position = $this->_db->get($this->_inode);
      if($position > $this->_max_position)
        $position = 0;
    }
    if(0 > fseek($this->_file, $position))
      throw new Exception('Failed set new position');
  }

  function readNextLine()
  {
    if(feof($this->_file))
      return false;
    $line = stream_get_line($this->_file, 8192, "\n");
    if($line === false)
      return false;
    if($this->_skip_save++ > 100)
       $this->_db->set($this->_inode, ftell($this->_file));
    return $line;
  }

  function __destruct()
  {
    if(is_resource($this->_file))
    {
      if(is_object($this->_db))
        $this->_db->set($this->_inode, ftell($this->_file));
      fclose($this->_file);
    }
  }
}
