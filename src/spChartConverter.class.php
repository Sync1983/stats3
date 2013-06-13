<?php

class spChartConverter extends spTools {
  
  private $_db;
  private $_presets;
  
  public function __construct() {
    parent::__construct();    
    $this->_db = $this->toolkit->getDefaultDbConnection();
    $presets = $this->_db->execute("SELECT id,name FROM preset");
    
    while ($row = $presets->fetch_assoc())      
      $this->_presets[$row['id']] = $row['name'];
  }
  
  public function createViewData($chart_vid,$data,$units) {        
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
    foreach ($data as $key=>$chart) {
      $parse_chart = array();
      $parse_keys = array();
      if(!is_array($chart))
        continue;
      
      foreach ($chart as $c_key=>$value) {
        unset($chart[$c_key]);         
        if($c_key<100000) {
          $parse_chart[]=array($c_key,$value*1);          
          $parse_keys[] = $c_key;
          $linear = true;
        } else
          $parse_chart[]=array($c_key*1000,$value*1);        
      }
            
      if(count($data)==0)
        $key = isset($this->_presets[$c_id])?$this->_presets[$c_id]:$key;
      if($type==0)
        $series[] = array('data'=>$parse_chart,'type'=>'spline','name'=>$key,'columns'=> $parse_keys,'units'=>$units);
      elseif($type==1)
        $series[] = array('data'=>$parse_chart,'type'=>'bar','name'=>$key,'columns'=>  array_keys($parse_chart),'units'=>$units);
      elseif($type==2)
        $series[] = array('data'=>$parse_chart,'type'=>'line','name'=>$key,'columns'=>  array_keys($parse_chart),'units'=>$units);
      elseif($type==3)
        $series[] = array('data'=>$parse_chart,'type'=>'areaspline','name'=>$key,'columns'=>  array_keys($parse_chart),'units'=>$units);
    }
    
    if($linear)
      return array( 'series'=>$series,                    
                    'xAxis'=>array(                        
                            'type'=>'linear',
                            'categories'=>$parse_keys,
                            'title'=>array('text'=>'Параметр'),                            
                          )                    
          );
    else
      return array('series'=>$series);
  }
  
}
