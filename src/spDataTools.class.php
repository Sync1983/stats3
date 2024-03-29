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
      'splitByValues' => array('arg_cnt'=>1),
  );
  
  private $_presets = array();
  private $_logger = array();
  private $_db = null;
  /** @var RedisLogger */
  private $_rd = null;
  private $_pid = 0;
  private $_start = 0;
  private $_stop = 0;
  private $_filter = null;
  
  public function __construct() {
    parent::__construct();
    $this->_db = $this->toolkit->getDefaultDbConnection();
    $presets = $this->_db->execute("SELECT name,data,project_id FROM preset");
    $this->_rd = new RedisLogger();
    while ($row = $presets->fetch_assoc())      
      $this->_presets[$row['project_id']."&".$row['name']] = $row['data'];
    
    $logger = $this->_db->execute("SELECT name,project_id,query FROM logger_chart");    
    while ($row = $logger->fetch_assoc())      
      $this->_logger[$row['project_id']."&".$row['name']] = $row['query'];
  }
  
  public function getFilter() {
    return $this->_filter;
  }

  public function getData($data,$project_id,$data_type,$chart_id,$start_time,$stop_time,$units="units",$filter=null){    
    $this->_pid = $project_id;
    $time = getdate(intval($start_time));
    $this->_start = mktime(0,0,0,$time["mon"],$time["mday"],$time["year"]);
    $this->_start += 3*3600;
    $time = getdate(intval($stop_time));
    $this->_stop = mktime(23,59,59,$time["mon"],$time["mday"],$time["year"]);    
    $this->_stop += 3*3600;
    $this->_filter = json_decode($filter,true);
    
    $result = array();
    if($data_type==0) {
      // This part not used now
      $roots = explode(',', $data);      
      foreach ($roots as $root) {
        $temp = $this->_pharse($root);        
        $result = array_merge($result,$this->calculate($temp,$root)); 
      }      
    } else if($data_type==1) {
      $queres = explode(";", $data);
      foreach ($queres as $data) {
        if($data=="")
          continue;
        $result = array_merge($result,$this->_getLoggerData($data));
      }      
    }    
    $result = $this->toolkit->createViewData($chart_id, $result, $units, $data_type);    
    return $result;
  }
  
  private function _filterConvert($filter) {    
    $result = "";
    foreach ($filter as $descr){      
      $result .= "`".$descr['item']."` ".$descr['operation']." ".$descr['value']." ";
      if(isset($descr['logic'])&&($descr['logic']!="-1"))
        $result .= $descr['logic']." ";
      else
        $result .= " ";
    }
    if($result!="")
      return "and (".$result.")";
    return "";
  }
  
  private function idToText($id) {    
    return $this->_rd->map_get($id, 'file', $this->_pid);
  }

  private function _getLoggerData($data) {
    $tstamp = "stamp>=".$this->_start." and stamp<=".  $this->_stop;    
    $rstamp = "reg_time>=".$this->_start." and reg_time<=".$this->_stop;
    $data = str_replace("@[stamp_round]", $tstamp, $data);
    $data = str_replace("@[pid]", "project_id=".$this->_pid, $data);
    $data = str_replace("@[time_range]", $rstamp, $data);
    $data = str_replace("@[filter]", $this->_filterConvert($this->_filter), $data);
    
    $y_fields = array();
    $matches = array();
    $reg_exp = "/as (y(\d*)_(\w*))/";
    preg_match_all($reg_exp, $data, $matches,PREG_SET_ORDER);
    
    foreach ($matches as $expr)
      $y_fields[$expr[1]] = array('name'=>$expr[3],'is_id'=>false);
    
    $data = str_replace("drop", "", $data);
    $data = str_replace("delete", "", $data);
    $data = str_replace("create", "", $data);
    $data = str_replace("DROP", "", $data);
    $data = str_replace("DELETE", "", $data);
    $data = str_replace("CREATE", "", $data);
    syslog(7,"SQL: ".$data);
//   echo "SQL: $data\r\n";a
    $result = array();
    $result = $this->_db->execute($data);    
    $charts = array();
    
    while($row = $result->fetch_assoc()) {      
      $ids = false;
      if(isset($row['x'])) {
        $x = $row['x'];
        if($x>10000000)
          $x-=10;
        unset($row['x']);
      } elseif (isset($row['x_id'])) {
        $x = $row['x_id'];
        unset($row['x_id']);
        $ids = true;
      }
      foreach ($row as $key=>$value) {
        $chart = $y_fields[$key]['name'];
        if($chart=="")
          continue;
        if($x=="")
          continue;
        if(!isset($charts[$chart]))
          $charts[$chart] = array();
        if(!$ids)
          $charts[$chart][$x] = $value;
        else
          $charts[$chart][$this->idToText ($x)] = $value;
      }
    }
    //  $return[$row['x']] = $row['y'];
    foreach ($charts as &$chart)
      ksort($chart);
    if(isset($ids)&&$ids) {      
      foreach ($charts as &$chart) {
        $keys = array_keys($chart);
        foreach($keys as $key=>$value) {
          unset($keys[$key]);
          $key = preg_replace('~[^0-9]+~','',$value); 
          $keys[$value]=$key;
        }
        asort($keys);
        $sort_chart = array();
        foreach ($keys as $key=>&$value)
          $sort_chart[$key] = $chart[$key];
        $chart = $sort_chart;
      }        
    }
    return $charts;
  }
  
  private function _pharse($root){        
    $preset_key = $this->_pid."&".$root;    
    if(array_key_exists($preset_key, $this->_presets)&&($root!=="")) {
      // Корень - это указатель на другую формулу, нужно подменить его            
      return $this->_pharse($this->_presets[$preset_key]);      
    }
    if($root=="")
      return null;    
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
  
  private function constructTree($root){    
    $start_pos = array_search('', $root);  
    $pos = 0;    
    //Находим первую команду выше 
    if($start_pos>=0)
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
      $root[0] = $this->constructTree ($root);
    return $root[0];
  }


  private function calculate($action,$root) {    
    if(!isset($action['f']))
      return array($action[0]=>$this->loadChart($action[0], $this->_start,  $this->_stop));    
    $funct = $action['f'];
    $params = $action['p'];       
    foreach ($params as $key=>$param)
      if(is_array($param)&&isset($param['f'])) {
        $calc_result = $this->calculate($param,$key); 
        $params[$key] = $calc_result[$key];
      }
    if(!method_exists($this, $funct)) {
      print_r($action);
      echo "Math action $action not found\r\n";
      return array();
    }    
    return $this->$funct($params,$root);
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
  
  private function div($params,$root) {      
    foreach ($params as $key=>$param)
      if(!is_array($param)&&isset($param['f']))
        $params[$key] = $this->loadChart($param, $this->_start,  $this->_stop);
    $result = array();    
    foreach ($params[0] as $key=>$value) {
      if(is_array($params[1]))
        $result[$key] = isset ($params[1][$key])?$value/$params[1][$key]:0;
      else
        $result[$key] = isset($params[1])?$value/$params[1]:0;
    }    
    return array($root=>$result);
  }
  
  private function diff($params,$root) {
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
    return array($root=>$result);
  }
  
  private function perc($params,$root) {
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
    return array($root=>$result);
  }
  
  private function split($params,$root) {
    $name   = $params[0];
    $axist  = $params[1];
    $start  = $this->_start;
    $stop   = $this->_stop;        
    $data = $this->_db->execute("SELECT stamp,value FROM counter2 WHERE name=\"$name\" and axist=\"$axist\" and project_id=".$this->_pid." and stamp>=$start and stamp<=$stop GROUP BY stamp,axist");
    $result = array();        
    while ($row = $data->fetch_assoc())
      $result[$row['stamp']] = $row['value'];
    return array($root=>$result);
  }
  
  private function sumM($params,$root) {
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
    return array($root=>$result);
  }
  
  private function  byFinDay($params,$root) {    
    $name   = $params[0];    
    //$start  = $this->_start;
    $stop   = $this->_stop;        
    $SQL = "SELECT axist,value FROM counter2 WHERE `name`=\"$name\" and project_id=".$this->_pid." and stamp BETWEEN (ROUND($stop/86400)-0.5)*86400 and (ROUND($stop/86400)+0.5)*86400 ORDER BY axist";
    $data = $this->_db->execute($SQL);    
    $result = array();        
    if(!$data) {
      echo $this->_db->error."\r\n";      
      return $result;
    }
    $num = true;
    while ($row = $data->fetch_assoc()){      
      if(!is_numeric($row['axist']))
        $num = false;
      $result[$row['axist']] = $row['value'];
    }
    if($num)
      ksort($result,SORT_NUMERIC);
    return array($root=>$result);
  }
  
   private function splitByValues($params,$root) {
     $name   = $params[0];
     $start  = $this->_start;
     $stop   = $this->_stop; 
     $data = $this->_db->execute("SELECT stamp,axist,value FROM counter2 WHERE name=\"$name\" and project_id=".$this->_pid." and stamp BETWEEN $start and $stop GROUP BY stamp,axist");
      $result = array();
      while ($row = $data->fetch_assoc()) {
        if(!isset($result[$row['axist']]))
          $result[$row['axist']] = array();
        $result[$row['axist']][$row['stamp']*1] = $row['value'];    
      }     
     return $result;
  }
  
  private function  sumAll($params,$root) {    
    $name   = $params[0];    
    $start  = $this->_start;
    $stop   = $this->_stop;        
    $SQL = "SELECT stamp, sum(value) as val FROM counter2 WHERE `name`=\"$name\" and project_id=".$this->_pid." and stamp BETWEEN $start and $stop GROUP BY ROUND(stamp/86400)";
    
    $data = $this->_db->execute($SQL);    
    
    $result = array();        
    if(!$data) {
      echo $this->_db->error."\r\n";      
      return $result;
    }
    
    while ($row = $data->fetch_assoc())
      $result[$row['stamp']] = $row['val'];
    
    //ksort($result,SORT_NUMERIC);
    return array($root=>$result);
  }
}
