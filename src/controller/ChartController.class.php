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
    while ($row = $db_result->fetch_assoc())
        $result[] = $row;
    
    // TODO понять как очистить результат
    //mysql_free_result($db->getConnectionId());
    
    /*foreach ($result as $key=>$data_item)
      $result[$key]['series'] = $this->toolkit->getData($data_item['data'],$project_id,$chart_id,$bday,$eday);*/
    $this->sendAjaxResponce(array('series'=> $this->toolkit->getData($result[0]['data'],$project_id,$view_char_id,$bday,$eday)));
    //$this->sendAjaxError("Project: " . $project_id . " id:" . $chart_id);
  }

}

?>
