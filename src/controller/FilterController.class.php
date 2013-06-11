<?php

class FilterController extends spController {

  function doAjaxLoadConstructor() {
    $this->view = $this->toolkit->createViewByTemplate('filter/ajax_load_constructor.phtml');    
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
