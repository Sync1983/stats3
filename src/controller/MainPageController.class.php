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
      $project_id = null;
    }
    
    $projects = array();
    $projects[]=array('id'=>-1,'title'=> 'Выберите проект');
    foreach ($project_ids as $p_id) {
      $item = array('id'=>$p_id,'title'=> lmbActiveRecord::findById('Project',$p_id)->get('title'),'select'=>"");
      if($p_id==$project_id)
        $item['select'] = "selected";
      $projects[] = $item;
    }
    $this->view->set('projects',  $projects);
    $this->view->set('project_id',  $project_id);
    if(!$project_id)
      return;
    
    $pages = array();    
    $sql_pages = lmbActiveRecord::find('Pager', array('criteria'=>"project_id=$project_id"));    
    foreach ($sql_pages as $record) {
      $page = new Pager();
      $page->loadFromRecord($record);
      $pages[]=$page;
    }
    $tabs = array();
    foreach ($pages as $page)
      $tabs[]=array('title'=>$page->get('name'),'id'=>$page->get('id'));    
    $this->setTimeInterval();
    $this->view->set('tabs', $tabs);
  }
  
  function doAjaxAddTab() {
    $request = $this->toolkit->request;    
    $project_id = $request['project_id'];   
    /** @var Member */
    $member = $this->toolkit->getMember();    
    $this->sendAjaxResponce(array('pid'=>$project_id,'mid'=>$member->getId()), true);    
  }
  
  function doAjaxSaveTab() {
    $request = $this->toolkit->request;
    $p_id = $request['pid'];
    $m_id = $request['mid'];
    /** @var Member */
    $member = $this->toolkit->getMember();    
    if((!$p_id)||(!$m_id)) {      
      $this->sendAjaxError('Not valid data struct');
      return;
    }
    $this->sendAjaxSuccess();
  }
          
  function doAjaxLoadPage() {
    $request = $this->toolkit->request;    
    $project_id = $request['project_id'];    
    $page_id    = $request['page_id'];
    $page_view = new PageView($page_id);
    $views = $page_view->getPageViews();    
    $charts = array();
    foreach ($views as $view) {
      $c_id = $view->get('counter_id');      
      $preset = new Preset($c_id);
      if($project_id!=$preset->getProjectId())
        continue;
      $charts['id'] = $c_id;
      $charts['name'] = $preset->getName();
    }
    
    $this->sendAjaxResponce($charts, true);
  }
  
  private function setTimeInterval() {
    $request = $this->toolkit->request;
    $bday = $request['bday'];
    $eday = $request['eday'];
    if(!$bday)
      $bday = strtotime ('-30 days');
    if(!$eday)
      $eday = time();
    $this->view->set('bday', $bday);
    $this->view->set('eday', $eday);
  }
}
