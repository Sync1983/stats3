<?php
/**
 * @author Sync
 */
class PageController extends spController {
  
  function doDisplay() {
    
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
    $this->view = $this->toolkit->createViewByTemplate('page/ajax_get_presets.phtml');
    $this->view->set('names', $preset_names);    
    $this->sendAjaxResponce($preset_names,true);
  }
  
  function doAjaxAddChart() {
    $request = $this->toolkit->request;
    $p_id = $request['project_id'];    
    $page = $request['page_id'];
    $counter_id = $request['counter_id'];
    /** @var Member */
    $member = $this->toolkit->getMember();
    $m_id = $member->getId();
  }
  
}