<?php

bit_lazy_class('FixedRowsInFile', 'bit/util/FixedRowsInFile.class.php');

class CollectionInFile
{
  protected $_fnav;

  protected $_format = null;
  protected $_raw_format;
  protected $_fields_ord;
  protected $_pack;

  function __construct($file_path, $format = null)
  {
    if(!is_null($format))
      $this->_format = $format;
    if(!$this->_format)
      throw new Exception('Define data format!');
    $this->_fields_ord = explode('/', $this->_format);
    foreach($this->_fields_ord as $key => $field)
    {
      $this->_raw_format .= $field[0];
      $this->_fields_ord[$key] = substr($field, 1);
    }
    $length_record = strlen(call_user_func_array('pack', array_pad(array($this->_raw_format), count($this->_fields_ord) + 1, 0)));
    $this->_fnav = new FixedRowsInFile($file_path, $length_record);
  }

  function flush()
  {
    $this->_fnav->flush();
  }

  function append($items)
  {
    $this->_fnav->append($this->_pack($items));  
  }

  function paginate($offset, $limit)
  {
    return $this->_unpack($this->_fnav->paginate($offset, $limit));
  }

  function count()
  {
    return $this->_fnav->count(); 
  }

  function maxOffset()
  {
    return $this->_fnav->maxOffset();
  }
  
  protected function _pack($items)
  {
    if(null === $this->_pack)
    {
      $args = array('\''.addslashes($this->_raw_format).'\'');
      foreach($this->_fields_ord as $field)
        $args[] = '$data[\''.addslashes($field).'\']';
      $this->_pack = create_function('$data', 'return pack('.implode(', ', $args).');');
    }
    $pack = $this->_pack;
    foreach($items as $key => $data)
      $items[$key] = $pack($data);
    return $items;
  }

  protected function _unpack($items)
  {
    foreach($items as $key => $data)
      $items[$key] = unpack($this->_format, $data);
    return $items;
  }
}
