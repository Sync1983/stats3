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
    //$projects[]=array('id'=>-1,'title'=> 'Выберите проект');
    foreach ($project_ids as $p_id) {
      $item = array('id'=>$p_id,'title'=> lmbActiveRecord::findById('Project',$p_id)->get('title'),'select'=>"");
      if($p_id==$project_id)
        $item['select'] = "selected";
      $projects[] = $item;
    }
    $this->view->set('projects',  $projects);
    $this->view->set('exit',"Выйти");
    $this->setTimeInterval();
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
  
  function doAjaxRenameTab() {
    $request = $this->toolkit->request;
    $p_id = $request['id'];    
    $name = $request['new_name'];    
    $tab = new Page();    
    $tab->loadById($p_id);
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
    $page_view = new PageView($page_id);
    $views = $page_view->getPageViews();    
    $this->view = $this->toolkit->createViewByTemplate('main_page/ajax_load_tab.phtml');
    $this->view->set('charts', $views);    
    $this->sendAjaxResponce(array('charts'=>$views),true);
  }
  
  function doAjaxDeleteChart() {
    $request = $this->toolkit->request;    
    $chart_id    = $request['chart_id'];  
    $page_id = ($chart_id>>16)&0xFFFF;
    $chart_id &= 0xFFFF;
    $page_view = new PageView($page_id);
    if($page_view->deleteChart($page_id, $chart_id))
      $this->sendAjaxSuccess();
    else
      $this->sendAjaxError ('Deleting error');
  }
  
  function doAjaxChangePositions () {
    $request = $this->toolkit->request;    
    $project_id   = $request['project_id'];  
    $page_id      = $request['page_id']; 
    $query        = $request['query']; 
    $page = new PageView($page_id);
    $charts = $page->getPageViews();
    
    $page->deleteAllCharts($page_id);
    foreach ($charts as $key=>$value) {
      $charts[$value['id']] = $value;
      unset($charts[$key]);
    }
    
    foreach ($query as $key => $value) {      
      $page = new PageView();
      $page->set('page_id',$page_id);
      $page->set('position',$key);
      $chart_vid = explode("_", $value);
      $chart_vid = $chart_vid[1];
      echo "Chart id: $chart_vid\r\n";
      $value = $charts[$chart_vid];     
      if(!isset($value['counter_id']))
        continue;
      $page->set('data_type',   isset($value['data_type'])  ?$value['data_type']  :0);
      $page->set('view_preset', isset($value['view_preset'])?$value['view_preset']:0);          
      $page->set('counter_id',  $value['counter_id']);
      $page->save();      
    };
    $this->sendAjaxSuccess();
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
