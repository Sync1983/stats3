<?php

class FilterController extends spController {

  function doAjaxLoadConstructor() {
    $request = $this->toolkit->request;
    $project_id = $request['project_id'];
    $rd = new RedisLogger();
    $keys = $rd->map_names($project_id);
    $redis_keys = array();
    foreach ($keys as $key) {
      $value = $rd->redis()->hget($key);
      $redis_keys[] = array('id'=>$key,'file'=>$value['file']);
    }
    $this->view = $this->toolkit->createViewByTemplate('filter/ajax_load_constructor.phtml');    
    $this->view->set('keys', $redis_keys);
    $this->sendAjaxResponce(array(),true);
  }
  
  function doAjaxSaveFilter() {
    $request = $this->toolkit->request;
    $project_id = $request['project_id'];
    $name = $request['name'];
    $data = json_encode($request['filter']);
    $filter = new Filter();
    $filter->set('project_id',$project_id);
    $filter->set('name',$name);
    $filter->set('data',$data);
    $filter->save();
    $this->sendAjaxSuccess();
  }
}
