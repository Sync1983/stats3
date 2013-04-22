<?php
/**
 * @author Sync
 */

class ChartController extends spController {
  
  function doAjaxLoadChart(){
    $request = $this->toolkit->request;    
    $project_id = $request['project_id'];
    $chart_id = $request['id'];
    /** @var Member */
    $member = $this->toolkit->getMember();
    $db = $this->toolkit->getDefaultDbConnection();            
    $result = $db->fetch("SELECT name FROM preset WHERE id=".$chart_id);
    //var_dump($result);        
    $this->sendAjaxError("Project: ".$project_id." id:".$chart_id." name: ");
  }
  
}

?>
