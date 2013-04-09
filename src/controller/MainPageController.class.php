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
      $project_id = $project_ids[0];
    }
    
    $pager = new Pager();
    $pager->loadByProject($project_id);
    $this->view->set()
  }
}
