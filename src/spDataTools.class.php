<?php

class spDataTools extends spTools {
  
  private $commands = array(
      'diff'  => array('arg_cnt'=>2),
      'div'   => array('arg_cnt'=>2),
      'mul'   => array('arg_cnt'=>2),
      'perc'  => array('arg_cnt'=>2),
      'split' => array('arg_cnt'=>2),
      'sumM'  => array('arg_cnt'=>1),
      'sumAll'    => array('arg_cnt'=>1),
      'byFinDay'  => array('arg_cnt'=>1),      
  );
  
  private $_raw_data = array();
  private $_presets = array();
  private $_db = null;
  private $_pid = 0;
  private $_start = 0;
  private $_stop = 0;
  
  public function __construct() {
    parent::__construct();
    $this->_db = $this->toolkit->getDefaultDbConnection();
    $presets = $this->_db->execute("SELECT name,data,project_id FROM preset");
    
    while ($row = $presets->fetch_assoc())      
      $this->_presets[$row['project_id']."&".$row['name']] = $row['data'];
  }

  public function getData($data,$project_id,$chart_id,$start_time,$stop_time,$units="units"){    
    $this->_pid = $project_id;
    $this->_start = $start_time;
    $this->_stop = $stop_time;
    
    $roots = explode(',', $data);
    $result = array();
    foreach ($roots as $root) {
      $temp = $this->_pharse($root);        
      $result[$root] = $this->calculate($temp);       
    }
    $result = $this->toolkit->createViewData($chart_id, &$result,$units);
    return $result;
  }
  
  private function _pharse($root){    
    $preset_key = $this->_pid."&".$root;    
    if(array_key_exists($preset_key, $this->_presets)) {
      // Корень - это указатель на другую формулу, нужно подменить его      
      return $this->_pharse($this->_presets[$preset_key]);
    }
    
    $parts = preg_split("/[\(#\)]/", $root);    
    // parts - набор команд и параметров    
    foreach ($parts as $key=>$part) {
      $preset_key = $this->_pid."&".$part;
      if(array_key_exists($preset_key, $this->_presets)) { 
        $parts[$key] = $this->_pharse($part);
      //Если формула просто alias, то заменяем им позицию, иначе вставляем в позицию дерево  
      if(count($parts[$key])==1)
        $parts[$key] = $parts[$key][0];
      }
    }
    if(count($parts)>1)
      $parts = $this->constructTree($parts);       
    return $parts;    
  }
  
  private function constructTree(&$root){    
    $start_pos = array_search('', $root);  
    $pos = 0;    
    //Находим первую команду выше    
    for($pos = $start_pos; $pos >= 0; $pos--)
      if(!is_array($root[$pos]) && array_key_exists($root[$pos], $this->commands))
        break;
    //Вырезаем часть с командой и параметрами
    $params = array_splice($root, $pos, $start_pos-$pos);            
    //Переводим первый элемент в команду
    $action = $params[0];
    //Вырезаем команду из параметров
    array_splice($params, 0, 1);
    //Удаляем пустой разделитель параметров
    array_splice($params, count($params), 1);        
    $root[$pos] = array('f'=>$action,'p'=>$params);    
    if(count($root)>1)
      $this->constructTree ($root);
    return $root[0];
  }


  private function calculate(&$action) {    
    if(!isset($action['f']))
      return $this->loadChart($action[0], $this->_start,  $this->_stop);;
    $funct = $action['f'];
    $params = $action['p'];    
    foreach ($params as $key=>$param)
      if(is_array($param)&&isset($param['f']))
        $params[$key] = $this->calculate($param);
    if(!method_exists($this, $funct)) {
      print_r($action);
      echo "Math action $action not found\r\n";
      return array();
    }    
    return $this->$funct($params);
  }
  
  private function loadChart($name,$start,$stop) {    
    $data = $this->_db->execute("SELECT stamp,axist,value FROM counter2 WHERE name=\"$name\" and project_id=".$this->_pid." and stamp BETWEEN $start and $stop GROUP BY stamp,axist");
    $result = array();
    while ($row = $data->fetch_assoc()) 
      $result[$row['axist']] = $row['value'];
    return $result;
  }
  
  private function loadChartWithStamp($name,$start,$stop) {    
    $data = $this->_db->execute("SELECT stamp,axist,value FROM counter2 WHERE name=\"$name\" and project_id=".$this->_pid." and stamp BETWEEN $start and $stop GROUP BY stamp,axist");
    $result = array();
    while ($row = $data->fetch_assoc()) 
      $result[$row['axist']][$row['stamp']] = $row['value'];
    return $result;
  }

  /*================================= Math part ==============================*/
  
  private function div($params) {     
    foreach ($params as $key=>$param)
      if(!is_array($param)&&isset($param['f']))
        $params[$key] = $this->loadChart($param, $this->_start,  $this->_stop);
    $result = array();    
    foreach ($params[0] as $key=>$value) {
      if(is_array($params[1]))
        $result[$key] = isset ($params[1][$key])?$value/$params[1][$key]:0;
      else
        $result[$key] = $value/$params[1];
    }    
    return $result;
  }
  
  private function diff($params) {
    foreach ($params as $key=>$param)
      if(!is_array($param)&&isset($param['f']))
        $params[$key] = $this->loadChart($param, $this->_start,  $this->_stop);
    $result = array();
    foreach ($params[0] as $key=>$value) {
      if(is_array($params[1]))
        $result[$key] = isset ($params[1][$key])?$value-$params[1][$key]:0;
      else
        $result[$key] = $value-$params[1];
    }
    return $result;
  }
  
  private function perc($params) {
    foreach ($params as $key=>$param)
      if(!is_array($param)&&isset($param['f']))
        $params[$key] = $this->loadChart($param, $this->_start,  $this->_stop);
    $result = array();
    foreach ($params[0] as $key=>$value) {
      if(is_array($params[1]))
        $result[$key] = isset ($params[1][$key])?$value*100/$params[1][$key]:0;
      else
        $result[$key] = $value*100/$params[1];
    }
    return $result;
  }
  
  private function split($params) {
    $name   = $params[0];
    $axist  = $params[1];
    $start  = $this->_start;
    $stop   = $this->_stop;        
    $data = $this->_db->execute("SELECT stamp,value FROM counter2 WHERE name=\"$name\" and axist=\"$axist\" and project_id=".$this->_pid." and stamp>=$start and stamp<=$stop GROUP BY stamp,axist");
    $result = array();        
    while ($row = $data->fetch_assoc())
      $result[$row['stamp']] = $row['value'];
    return $result;
  }
  
  private function sumM($params) {
    foreach ($params as $key=>$param)
      if(!is_array($param)&&isset($param['f']))
        $params[$key] = $this->loadChartWithStamp($param, $this->_start,  $this->_stop);    
    $result = array();
    foreach($params[0] as $key=>$values) {
      $sum = 0;
      foreach ($values as $value)        
        $sum += $value;
      $result[$key] = $sum;
    }    
    ksort($result,SORT_NUMERIC);
    return $result;
  }
  
  private function  byFinDay($params) {
    $name   = $params[0];    
    //$start  = $this->_start;
    $stop   = $this->_stop;        
    $data = $this->_db->execute("SELECT axist,value FROM counter2 WHERE name=\"$name\" and project_id=".$this->_pid." and stamp=$stop ORDER BY axist");
    $result = array();        
    while ($row = $data->fetch_assoc())
      $result[$row['axist']] = $row['value'];
    return $result;
  }
  
}
