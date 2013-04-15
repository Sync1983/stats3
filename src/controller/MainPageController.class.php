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
    $sql_pages = lmbActiveRecord::find('Page', array('criteria'=>"project_id=$project_id"));    
    foreach ($sql_pages as $record) {
      $page = new Page();
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
    $name = $request['name'];
    /** @var Member */
    $member = $this->toolkit->getMember();    
    if((!$p_id)||(!$m_id)||(!$name)) {      
      $this->sendAjaxError('Not valid data struct');
      return;
    }
    $tab = new Page();
    $tab->set('member_id', $member->getId());
    $tab->set('project_id', $p_id);
    $tab->set('name',$name);
    $tab->save();
    $this->sendAjaxSuccess();
  }
  
  function doAjaxDeleteTab() {
    $request = $this->toolkit->request;
    $id = $request['id'];    
    /** @var Member */
    $member = $this->toolkit->getMember();    
    if((!$id)||(!$member)) {      
      $this->sendAjaxError('Not valid data struct');
      return;
    }
    $tab = new Page($id);    
    $tab->delete('Page',"id=".$id);
    $this->sendAjaxSuccess();
  }
          
  function doAjaxLoadTab() {
    $request = $this->toolkit->request;    
    $project_id = $request['project_id'];    
    $page_id    = $request['page_id'];
    $charts = array();
    $page_view = new PageView($page_id);
    $views = $page_view->getPageViews();    
    $this->view = $this->toolkit->createViewByTemplate('main_page/ajax_load_tab.phtml');
    $this->view->set('charts', $views);    
    $this->sendAjaxResponce($charts,true);
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
