<?php

class PageController extends spController {
  
  function doDisplay() {
    $request = $this->toolkit->request;
    $p_id = $request['project_id'];        
    
    $this->setTemplate('page/display.phtml');
    $title = lmbActiveRecord::findById('Project',$p_id)->get('title');
    $this->view->set('title',  $title); 
    $page_model = new Page();
    $pages = $page_model->findAllRecords("project_id=$p_id");    
    $this->view->set('pages', $pages); 
    $this->view->set('active_id',isset($pages[0])?$pages[0]->get('id'):-1);
    $this->view->set('project_id', $p_id); 
    
    $filter = new Filter();        
    $this->view->set('filters', $filter->getFiltersForProject($p_id));
    
    $this->view->set('exit',"Выйти");
    $this->setTimeInterval();
  }
  
  function doAjaxGetPresets() {
    $request = $this->toolkit->request;
    $p_id = $request['project_id'];    
    $page = $request['page_id'];
    /** @var Member */
    $member = $this->toolkit->getMember();
    $m_id = $member->getId();
    $preset = new Preset();
    $presets = $preset->findAllRecords('project_id='.$p_id,array('name'=>'ASC'));     
    $preset_names = array();
    foreach ($presets as $preset)
      array_push ($preset_names, array('id'=>$preset['id'],'title'=>$preset['name']));
    
    $logger = new LoggerChart();
    $loggers = $logger->findAllRecords('project_id='.$p_id,array('name'=>'ASC'));     
    $logger_names = array();
    foreach ($loggers as $logger)
      array_push ($logger_names, array('id'=>$logger['id'],'title'=>$logger['name']));
    
    $this->view = $this->toolkit->createViewByTemplate('page/ajax_get_presets.phtml');
    $this->view->set('names', $preset_names);    
    $this->view->set('logger_names', $logger_names);    
    $this->sendAjaxResponce($preset_names,true);
  }
  
  function doAjaxAddChart() {
    $request = $this->toolkit->request;
    $p_id = $request['project_id'];    
    $page = $request['page_id'];
    $data_type = isset($request['data'])?$request['data']:0;
    $counter_id = $request['counter_id'];
    /** @var Member */
    $member = $this->toolkit->getMember();
    $m_id = $member->getId();
    if((!$m_id)||(!$p_id)||(!$counter_id)||(!$page)) {
      $this->sendAjaxError('Error in add chart data');      
      return;
    }
    $db_page = new PageView($page);
    $count = count($db_page->getPageViews());
    $db_page = new PageView($page);
    $db_page->set('page_id',$page);
    $db_page->set('position',$count+1);
    $db_page->set('data_type',$data_type);
    $db_page->set('counter_id',$counter_id);
    $db_page->set('view_preset',0);
    $db_page->save();
    $this->sendAjaxSuccess();
  }
  
  function doAjaxUpdateChart() {
    
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