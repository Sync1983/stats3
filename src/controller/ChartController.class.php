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
    /*$db = $this->toolkit->
            new mysqli(lmb_env_get("DB_HOST"), lmb_env_get("DB_USER"), lmb_env_get("DB_PASS"),lmb_env_get("DB"));    
    $result = $db->query("SELECT name FROM preset WHERE id=".$chart_id);
    var_dump($result);*/
    var_dump($this->toolkit);
    $this->sendAjaxError("Project: ".$project_id." id:".$chart_id." name: ".$result('name'));
  }
  
}

?>
