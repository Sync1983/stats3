<?php

class FixedRowsInFile
{
  protected $_file_path;
  protected $_length;

  function __construct($file, $length_record)
  {
    $this->_file_path = $file;
    $this->_length = $length_record;
    $this->_resource = fopen($this->_file_path, 'a+');
  }

  function flush()
  {
    fclose($this->_resource);
    $this->_resource = fopen($this->_file_path, 'w+');
  }

  function __destruct()
  {
    if(is_resource($this->_resource))
      fclose($this->_resource);
  }

  function count()
  {
    fseek($this->_resource, 0, SEEK_END);
    return (int) (ftell($this->_resource) / $this->_length);
  }

  function maxOffset()
  {
    return $this->count() * $this->_length;
  }

  function paginate($offset, $limit)
  {
    $length = $this->_length;
    $limit = max(0, min($offset + $limit, $this->count()) - $offset);
    if(!$limit)
      return array();
    if(0 < fseek($this->_resource, $offset*$length))
      throw new Exception('Failed set position: '.($offset.$length));
    return str_split(fread($this->_resource, $limit*$length), $length);
  }

  function append($strings)
  {
    $count = count($strings);  
    $strings = implode('', $strings);
    $len = strlen($strings);
    if($count != ($len / $this->_length))
      throw new Exception('Bad length string');
    if($len !== file_put_contents($this->_file_path, $strings, FILE_APPEND | LOCK_EX))
      throw new Exception('Failed write in file: '.$this->_file_path);
    //fseek($this->_resource, 0, SEEK_END);
    //if($len !== fwrite($this->_resource, $strings, $len))
    //  throw new Exception('Failed write in file: '.$this->_file_path);
  }
}
