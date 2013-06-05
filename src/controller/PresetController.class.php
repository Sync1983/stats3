<?php
/**
 * @author Sync
 */
class PresetController extends spController {
  
  function doDisplay() {
    
  }
  
  function doAjaxGetPage() {
    $request = $this->toolkit->request;
    $p_id = $request['project_id'];
    
    $preset = new Preset();
    $presets = $preset->findAllRecords('project_id='.$p_id,array('name'=>'ASC'));   
    $logger = new LoggerChart();
    $loggers = $logger->findAllRecords('project_id='.$p_id,array('name'=>'ASC'));
    $this->view = $this->toolkit->createViewByTemplate('preset/ajax_get_page.phtml');
    $this->view->set('presets', $presets);    
    $this->view->set('loggers', $loggers);    
    $this->sendAjaxResponce(array(),true);
  }
  
  function doAjaxChangeRow () {
    $request = $this->toolkit->request;
    $p_id = $request['pid'];
    $type = $request['table'];
    $id = $request['row_id'];
    $name = $request['name'];
    $value = $request['value'];
    
    if(!$p_id||!$type||!$id||!$name||!$value)
      return;
    $control = null;
    if($type == "preset")
      $control = new Preset();
    else if($type=="logger")
      $control = new LoggerChart();
    $class_name = $control->getTable();    
    $item = $control->findOneBySql($control->getClass(), "SELECT * FROM $class_name WHERE project_id=$p_id and id=$id");
    $item->set($name,$value);
    $item->save();
    echo $value;
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
  
}