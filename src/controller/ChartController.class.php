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
    $bday = $request['bday'];
    $eday = $request['eday'];
    /** @var Member */
    $member = $this->toolkit->getMember();

    $db = $this->toolkit->getDefaultDbConnection();    
    $db_result = $db->execute("SELECT * FROM preset WHERE id=" . $chart_id);    
    $result = array();
    while ($row = $db_result->fetch_assoc()) {
        $result[] = array('series' => 
                          $this->toolkit->getData(
                                  $row['data'],
                                  $project_id,
                                  $view_char_id,
                                  $bday,
                                  $eday));
    };
    
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

}

?>
