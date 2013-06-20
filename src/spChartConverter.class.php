<?php

class spChartConverter extends spTools {
  
  private $_db;
  private $_presets;
  
  private $_type_to_text= array(0=>'spline',1=>'bar',2=>'line',3=>'areaspline');
  
  public function __construct() {
    parent::__construct();    
    $this->_db = $this->toolkit->getDefaultDbConnection();
    $presets = $this->_db->execute("SELECT id,name FROM preset");
    
    while ($row = $presets->fetch_assoc())      
      $this->_presets[$row['id']] = $row['name'];
  }
  
  public function createViewData($chart_vid,$data,$units,$data_type) {        
    $chart_id = $chart_vid&0xFFFF;
    $page_id = ($chart_vid>>16)&0xFFFF;        
    
    $chart = $this->_db->execute("SELECT view_preset,counter_id FROM page_view WHERE position=$chart_id and page_id=$page_id");
    $row = $chart->fetch_assoc();    
    
    $type = $row['view_preset'];
    $c_id = $row['counter_id'];
    if(!is_array($data))
      return array();
    $series = array();    
    $linear = false;
    //print_r($data);
    $keys = array();
    foreach ($data as $key=>$chart) {
      if(!is_array($chart))
        continue;
      foreach ($chart as $c_key=>$value)
        $keys[$c_key] = $c_key;
    }
    
    foreach ($data as $name=>$chart) {
      if(!is_array($chart))
        continue;
      
      $data_array = array();      
      foreach ($keys as $key) {
        if($key<100000) {
          $data_array[] = array($key,isset($chart[$key])?$chart[$key]*1:0);
          $linear = true;
        } else
          $data_array[] = array($key*1000,isset($chart[$key])?$chart[$key]*1:0);
      }
      
      /*foreach ($chart as $c_key=>$value) {
        unset($chart[$c_key]);         
        if($c_key<100000) {
          $parse_chart[]=array($c_key,$value*1);          
          $parse_keys[] = $c_key;
          $linear = true;
        } else
          $parse_chart[]=array($c_key*1000,$value*1);        
      }*/
      
      $series[] = array('data'=>array_values($data_array), 'name'=>$name, 'columns'=> array_keys($keys), 'units'=>$units, 'type'=>$this->_type_to_text[$type]);       
    }
    
    if($linear)
      return array( 'series'=>$series,                    
                    'xAxis'=>array(                        
                            'type'=>'linear',
                            'categories'=>array_values($keys),
                            'title'=>array('text'=>'Параметр'),                            
                          )                    
          );
    else
      return array('series'=>$series);
  }
  
}
