<?php

/**
 * @author Sync
 */
class ChartController extends spController {

  function doAjaxLoadChart() {
    $request = $this->toolkit->request;
    $project_id = $request['project_id'];
    $chart_id = $request['id'];
    $view_char_id = $request['vid'];
    $data_type = $request['data_type'];
    $bday = $request['bday'];
    $eday = $request['eday'];
    $filter = $request['filter'];
    /** @var Member */
    $member = $this->toolkit->getMember();

    $db = $this->toolkit->getDefaultDbConnection(); 
    if($data_type==0) {
      $db_result = $db->execute("SELECT * FROM preset WHERE id=" . $chart_id);    
      $result = array();
      while ($row = $db_result->fetch_assoc()) {
          $result[] = $this->toolkit->getData(
                                    $row['data'],
                                    $project_id,
                                    0,
                                    $view_char_id,
                                    $bday,
                                    $eday,
                                    $row['v_name'],
                                    $filter);
      };
    } else if($data_type==1){
      $db_result = $db->execute("SELECT * FROM logger_chart WHERE id=" . $chart_id);    
      $result = array();
      while ($row = $db_result->fetch_assoc()) {
          $result[] = $this->toolkit->getData(
                                    $row['query'],
                                    $project_id,
                                    1,
                                    $view_char_id,
                                    $bday,
                                    $eday,
                                    $row['y_values'],
                                    $filter);
      };
    }
    if(count($result)>0)
      $this->sendAjaxResponce($result[0]);
    else
      $this->sendAjaxError("Empty answer for project:$project_id chart id: $view_char_id start $bday end $eday");
  }
  
  function doAjaxChangeViewChart() {
    $request = $this->toolkit->request;
    $vid  = $request['vid'];
    $type = $request['type'];
    
    $chart_id = $vid&0xFFFF;
    $page_id = ($vid>>16)&0xFFFF;
    
    $page_view = new PageView($page_id);
    if($page_view->changeChartType($page_id, $chart_id, $type))
      $this->sendAjaxSuccess ();
    else
      $this->sendAjaxError ('Type not changed');
  }
  
  function doAjaxAddPresetLine () {
    $request = $this->toolkit->request;
    $pid = $request['pid'];
    $type = $request['type'];
    if($type == "preset")
      $control = new Preset();
    else if($type=="logger")
      $control = new LoggerChart();
    
    $control->set('project_id',$pid);    
    $control->save();
    $this->sendAjaxResponce(array('type'=>$type,'item'=>array($control->get('id'),$control->get('name'),0,0,0)));
  }

}