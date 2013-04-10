<?php

class MainPageController extends spController
{
  function doDisplay() {
    $request = $this->toolkit->request;
    $project_id = $request['project_id'];
    /** @var Member */
    $member = $this->toolkit->getMember();
    $project_ids = $member->getProjectAccessIds();
    if(!$project_id || !in_array($project_id, $project_ids)) {
      $project_id = isset($project_ids[0])?$project_ids[0]:null;
    }
    
    $projects = array();
    foreach ($project_ids as $p_id)
      $projects[]=array('id'=>$p_id,'title'=> lmbActiveRecord::findById('Project',$p_id)->get('title'));
    
    $pages = array();
    if(!$project_id)
      return;
    $sql_pages = lmbActiveRecord::find('Pager', array('criteria'=>"project_id=$project_id"));    
    foreach ($sql_pages as $record) {
      $page = new Pager();
      $page->loadFromRecord($record);
      $pages[]=$page;
    }
    $tabs = array();
    foreach ($pages as $page)
      $tabs[]=array('name'=>$page->get('name'),'id'=>$page->get('id'));
    $tabs[]=array('name'=>"+",'id'=>-1);
    $this->view->set('projects',  $projects);
    $this->view->set('tabs',  json_encode($tabs));
  }
}
