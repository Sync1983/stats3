<?php

lmb_require('limb/dbal/src/criteria/lmbSQLCriteria.class.php');

class StatsLoggerController extends lmbController {
   
public function doFromClient()
{
  if(!$project = $this->_getProject())
      return;
  $rd = new Logger();    
  $post = $this->request->getPost('event');  
  foreach ($post as $event)  
      $rd->addRedisItem($project->id,$event);
  $response = array('success'=>'ok');  
  $this->response->write(json_encode($response));  
  exit(0);
}

private function _getProject()
{
    $key = $this->request->getPost('apiKey');    
    if(!$key || !$project = lmbActiveRecord :: findOne('Project', lmbSQLCriteria :: equal('api_key', $key)))    
    {
      echo "key error!\r\n";
      return false;
    }
    return $project;
}
  
}
?>
