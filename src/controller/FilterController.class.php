<?php

class FilterController extends spController {

  function doAjaxLoadConstructor() {
    $request = $this->toolkit->request;
    $project_id = $request['project_id'];
    $rd = new RedisLogger();
    $keys = $rd->map_names($project_id);
    $tmp = array();
    $redis_keys = array();
    foreach ($keys as $key) 
      $tmp[$rd->redis()->hget($key,'file')] = str_replace (RedisLogger::MAP_PREFIX.$project_id."::", "", $key);    
    ksort($tmp,6);
    foreach ($tmp as $key => $value)
      $redis_keys[] = array('id'=>$value*1,'file'=> $key);
    
    $this->view = $this->toolkit->createViewByTemplate('filter/ajax_load_constructor.phtml');    
    $this->view->set('keys', $redis_keys);
    $this->sendAjaxResponce(array(),true);
  }
  
  function doAjaxSaveFilter() {
    $request = $this->toolkit->request;
    $project_id = $request['project_id'];
    $name = $request['name'];
    $data = json_encode($request['filter']);    
    //$find = lmbActiveRecord::findOne("Filter", array('name'=>$name,'project_id'=>$project_id),FALSE);        
    $find = new Filter();
    /** @var mysqli **/
    $db  = $find->getDefaultConnection();
    $SQL = "SELECT id FROM filter where `project_id`=$project_id and `name`='$name' LIMIT 0,1";
    $result = $db->execute($SQL);
    $row = $result->fetch_assoc();
    if(!$row) {
      $find->set('project_id',$project_id);
      $find->set('name',$name);    
      $find->set('data',$data);
      $find->save();    
    } else {
      $SQL = "UPDATE filter SET `data`='$data' WHERE id=".$row['id'];
      $db->execute($SQL);
    }      
    $SQL = "SELECT * FROM filter where `project_id`=$project_id";    
    $result = $db->execute($SQL);
    $return = array();
    while($row = $result->fetch_assoc())
      $return[]=$row;
    $this->sendAjaxResponce($return);
  }
}
