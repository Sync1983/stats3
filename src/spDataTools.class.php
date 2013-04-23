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
      $this->_presets[$row['project_id']."_".$row['name']] = $row['data'];
  }

  public function getData($data,$project_id,$start_time,$stop_time){    
    $this->_pid = $project_id;
    $this->_start = $start_time;
    $this->_stop = $stop_time;
    
    $roots = explode(',', $data);
    $result = array();
    foreach ($roots as $root) {
      $result[$root] = $this->_pharse($root);
    }
    return $result;
  }
  
  private function _pharse($root){
    echo "parse root: $root \r\n";
    $preset_key = $this->_pid."_".$root;
    if(array_key_exists($preset_key, $this->_presets)) {
      // Корень - это указатель на другую формулу, нужно подменить его
      return $this->_pharse($this->_presets[$preset_key]);
    }    
    
    $parts = split("[\(#\)]", $root);
    /*while($root!="") {
      if( (substr($root,$pos,1)=='(')||
          (substr($root,$pos,1)=='#')||
          (substr($root,$pos,1)==')')||
          ($pos>strlen($root))) {
        
            while(  (substr($root,$pos+1,1)=='(')||
                    (substr($root,$pos+1,1)=='#') ) 
                    $pos++;        
            
            $substr = substr($root, $start, $pos-$start);        
            $root = substr($root, $pos, strlen($root)-$pos+1);        
            $start=$pos = 1;
            array_push($parts, $substr);        
      } 
      $pos++;      
    }   
    foreach ($parts as $key=>$value) { 
      $parts[$key] = trim ($value, "(#");
      if($value=='')
        unset($parts[$key]);
    }*/
    // parts - набор команд и параметров
    print_r($parts);    
    $parts = $this->constructTree(&$parts);    
    print_r($parts);    
    //print_r($this->calculate($parts));
  }
  
  private function constructTree(&$root){
    
    $params = array();
    $pos = array_search('', $root)-1;    
    for(;$pos>0;$pos--) {
      if(in_array($root[$pos], $this->commands)) {
        return array('f'=>$root[$pos],'p'=>$params);
        array_splice($root, $pos+1, array_search('', $root)-$pos-1);
        $params = array();
      }
      array_unshift($params, $root[$pos]);
    }
    $action = $root[$pos];
    print_r($root);
    return array('f'=>$action,'p'=>$params);
  }


  private function calculate(&$root,$pos=0) {    
    $action = $root[$pos];
    $pos++;    
    $params = array();
    $count = count($root);
    for(;$pos<$count;$pos++) {
      if(in_array($root, $this->commands)) {
        // Этот параметр указатель на другую операцию, нужно сначала вычислить его
        $root[$pos] = $this->calculate($root,$pos);        
      }
      $preset_key = $this->_pid."_".$root[$pos];
      if(array_key_exists($preset_key, $this->_presets)) {
        // Этот параметр указатель на другую формулу, нужно сначала вычислить его
        $root[$pos] = $this->_pharse($this->_presets[$preset_key]);
        echo "Insert\r\n";
        print_r($root[$pos]);
      }
      $params[] = $root[$pos];
      unset($root[$pos]);
    }     
    
    echo "Calculate:\r\n";
    print_r($root);
    echo "Action: $action\r\n";
    print_r($params);
    
    if(!method_exists($this, $action)) {
      echo "Math action $action not found\r\n";
      return array();
    }
    return $this->$action($params);
  }
  
  private function loadChart($name,$start,$stop) {    
    $data = $this->_db->execute("SELECT axist,value FROM counter2 WHERE name=\"$name\" and project_id=".$this->_pid." and stamp BETWEEN $start and $stop GROUP BY stamp,axist");
    $result = array();
    while ($row = $data->fetch_assoc()) 
      $result[$row['axist']] = $row['value'];
    return $result;
  }


  /*================================= Math part ==============================*/
  
  private function div($params) {
    foreach ($params as $key=>$name)
      $params[$key] = $this->loadChart($name, $this->_start, $this->_stop);
    $result = array();
    foreach ($params[0] as $key=>$value) {
      if(is_array($params[1]))
        $result[$key] = isset ($params[1][$key])?$value/$params[1][$key]:0;
      else
        $result[$key] = $value/$params[1];
    }
    return $result;
  }
  
  private function perc($params) {
    foreach ($params as $key=>$name)
      $params[$key] = $this->loadChart($name, $this->_start, $this->_stop);
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
    $data = $this->_db->execute("SELECT stamp,value FROM counter2 WHERE name=\"$name\" and axist=\"$axist\" and project_id=".$this->_pid." and stamp BETWEEN $start and $stop GROUP BY stamp,axist");
    $result = array();
    while ($row = $data->fetch_assoc()) 
      $result[$row['stamp']] = $row['value'];
    return $result;
  }
  
}
