<?php

class spDataTools extends spTools {
  
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
    echo "parse root: $root \r\n";    
    $start = 0;
    $pos = 0;
    $parts = array();
    while($root!="") {
      if( (substr($root,$pos,1)=='(')||
          (substr($root,$pos,1)=='#')||
          (substr($root,$pos,1)==')')||
          ($pos>strlen($root))) {
        $substr = substr($root, $start, $pos-$start);        
        while(  (substr($root,$pos+1,1)=='(')||
                (substr($root,$pos+1,1)=='#')||
                (substr($root,$pos+1,1)==')')) 
                $pos++;        
        $root = substr($root, $pos, strlen($root)-$pos+1);        
        $start=$pos = 1;
        array_push($parts, $substr);
        echo $root."\r\n";        
      } 
      $pos++;      
    }    
    print_r($parts);
  }
  
}
