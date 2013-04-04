<?php

class SquareLocation
{
  protected $_bin_data;
  protected $_len;
  protected $_side;

  function __construct($bin_data, $side)
  {
    $this->_len = strlen($bin_data);
    $this->_side = $side;
    if((pow($side, 2)) !== $this->_len)
      throw new Exception('Bad data! Len: '.strlen($bin_data));
    $this->_bin_data = $bin_data;  
  }

  function getSide()
  {
    return $this->_side;  
  }

  function set($x, $y, $type)
  {
    $this->_bin_data[$x + $this->_side * $y] = chr($type);
  }
  
  function get($x, $y)
  {
    return ord($this->_bin_data[$x + $this->_side * $y]);
  }

  function getMap()
  {
    $list = unpack('c*', $this->_bin_data);
    $side = $this->_side;
    $map = array();
    $c = 0;
    foreach($list as $type)
      $map[$c % $side][$c++ / $side] = $type;
    return $map;
    //$c = 0;
    //$max = $this->_len;
    //$side = $this->_side;
    //$map = array();
    //for($c = 0; $c < $max; $c++)
    //  $map[$c % $side][$c / $side] = ord($this->_bin_data[$c]);
    //return $map;
  }

  function setMap($map)
  {
    $data = array('c*');
    foreach($map as $x => $row)
      foreach($row as $y => $type)
        $data[] = $type;
    $this->_bin_data = call_user_func_array('pack', $data);
  }

  function getData()
  {
    if($this->_len != strlen($this->_bin_data))
      throw new Exception('Bad changes! Len: '.strlen($this->_bin_data));
    return $this->_bin_data;
  }

  static function init($side, $type)
  {
    $class = get_called_class();
    return new $class(str_pad('', pow($side, 2), chr(intval($type))), $side);  
  }
}
