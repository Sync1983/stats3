<?php

lmb_require('limb/dbal/src/criteria/lmbSQLCriteria.class.php');

class ApiController extends lmbController
{
    
  public static function strArray($ar)
  {
    $Str = ""; 
    foreach($ar as $k=>$v)
    {
      if(is_array($v))
      {
        $Str .= $k.' is Array :'."\n      ".ApiController::strArray($v)."\n";
      }
      else       
        $Str .= $k.':'.$v."\n";      
    }
    return $Str;
  }
  
  private function _getProject()
  {
    $key = $this->request->getPost('key');    
    if(!$key || !$project = lmbActiveRecord :: findOne('Project', lmbSQLCriteria :: equal('api_key', $key)))
      return $this->_error('Bad key') && false;
    return $project;
  }

  function doFetchStats()
  {
    if(!$project = $this->_getProject())
      return;
    $fields = $this->request->getPost('fields');
    if(!is_array($fields))
      return $this->_error('Bad fields value '.json_encode($fields));
    
    $date = (int) $this->request->getPost('date');

    $response = array();

    $stproject = $this->toolkit->getStatsManager()->getProject($project->id);
    $vmetric = $stproject->getStatsMetricVisit();

    foreach(array_unique($fields) as $field)
    {
      switch($field)
      {
        case 'mau':
          $mau = $vmetric->fetchMau($date, $date);
          $response['mau'] = $mau ? array_shift($mau) : 0;
          break;
        case 'wau':
          $wau = $vmetric->fetchWau($date, $date);
          $response['wau'] = $wau ? array_shift($wau) : 0;
          break;
        case 'dau':
          $dau = $vmetric->fetchDauForPeriod($date, $date);
          $response['dau'] = $dau ? array_shift($dau) : 0;
          break;
        case 'active':
          $response['active'] = $vmetric->fetchActiveByDay($date);
          break;
        case 'new_active':
          $response['new_active'] = $vmetric->fetchNewActiveByDay($date);
          break;
      }
    }
    $this->response->write(json_encode($response));
  }

  function doGetReferrers()
  {
    if(!$project = $this->_getProject())
      return;
    $response = array();
    foreach(lmbActiveRecord :: find('Referrer', sprintf('project_id=%d', $project->id)) as $referrer)
      $response[$referrer->uid] = array('title' => $referrer->title, 'uid' => $referrer->uid, 'cname' => $referrer->cname);
    $this->response->write(json_encode($response));
  }

  function doSetCounterValue()
  {
    if(!$project = $this->_getProject())
      return;
    $name = $this->request->getPost('name');
    $value = $this->request->getPost('value');
    $date = $this->request->getPost('date');
    
    $manager = $this->toolkit->getStatsManager();
    $manager->is_ready_only = false;
    if(!$manager->getInfo()->hasHiddenCounter($project->id, $name))
      return $this->_error('Hidden counter \''.$name.'\' not isset');
    if(!is_numeric($value))
      return $this->_error('Bad counter value '.json_encode($value));
    if(!is_numeric($date))
      return $this->_error('Bad date value '.json_encode($date));

    $stproject = $manager->getProject($project->id);
    $metric = $stproject->getStatsMetricCounters();
    $metric->rawSetCounter($date, $name, $value);
    $metric->flush();
    $this->_success();
  }
  
  function doAddReferers()
  {
    if(!$project = $this->_getProject())
      return;
    $referers = isset($_POST['referers']) ? $_POST['referers'] : null;
    if(!is_array($referers))
      return $this->_error('Field referers is not array');
    $conn = lmbToolkit :: instance()->getDefaultDbConnection();
    foreach($referers as $uid => $title)
    {
      if(strlen($uid) > 5)
        return $this->_error('Max len field "uid" - 5, value: "'.$uid.'"');
      $ref = lmbActiveRecord :: findOne('Referrer', sprintf('project_id=%d AND uid=\'%s\'', $project->get('id'), $conn->escape($uid)));
      if(!$ref)
      {
        $ref = new Referrer;
        $ref->set('uid', $uid);
        $ref->set('project_id', $project->get('id'));
      }
      $ref->set('title', $title);
      $ref->saveSkipValidation();
    }
    $this->_success();
  }
  
  function doSetCounter2Values()
  {
    if(!$project = $this->_getProject())
      return;
    $date = $this->request->getPost('date');
    $datas = $this->request->getPost('datas');    

    foreach ($datas as $line) {
      if((isset($line["axist"]))&&(isset($line["value"])))
      {
        $name = $line["axist"];
        $value = $line["value"];
        //$hash = $line["hash"];
        //Counter2::removeByOldValues($project->id,$line["name"],$date ,$name);
        Counter2::addNewPair($project->id, $date, $line["name"], $name, $value)  ;
      }
      else {
        $counter_name = $line["name"];
        unset($line["name"]);
        $axists = array_keys($line);
        foreach ($axists as $axist) {
          $name = $axist;
          $value = $line[$axist];
          if($name&&$value)
          { 
            //Counter2::removeByOldValues($project->id,$counter_name,$date,$name);
            Counter2::addNewPair($project->id, $date, $counter_name, $name, $value)  ;
          }
        }
      }
    }
    
    $this->_success();
  }

  function doSetCountersValues()
  {
    if(!$project = $this->_getProject())
      return;
    $date = $this->request->getPost('date');
    $values = $this->request->getPost('values');

    if(!is_array($values))
      return $this->_error('Values not array');
    if(!is_numeric($date))
      return $this->_error('Bad date value '.json_encode($date));

    $manager = $this->toolkit->getStatsManager();
    $manager->is_ready_only = false;
    foreach($values as $name => $value)
    {
      if(!is_numeric($value))
        return $this->_error('Bad counter value '.json_encode($value));
    }

    $names = array_keys($values);
    $names = $manager->getInfo()->filterNotHiddenCounters($project->id, $names);
    if($names)
      return $this->_error('Hidden counters not isset: '.implode(',', $names));

    $stproject = $manager->getProject($project->id);
    $metric = $stproject->getStatsMetricCounters();
    foreach($values as $name => $value)
      $metric->rawSetCounter($date, $name, $value);
    $metric->flush();

    $this->_success();
  }

  function doSetCounters()
  {
    $this->_updateCounters(true);
  }
  
  function doAddCounters()
  {
    $this->_updateCounters(false);
  }

  protected function _updateCounters($is_replace)
  {
    if(!$project = $this->_getProject())
      return;

    $by_uid = array();
    foreach($project->get('counters') as $counter)
      $by_uid[$counter->get('uid')] = $counter;

    $new_counters = isset($_POST['new_counters']) ? $_POST['new_counters'] : null;
    if(!is_array($new_counters))
      return $this->_error('Field new_counters is not array');
    
    $new_charts = isset($_POST['charts']) ? $_POST['charts'] : null;
    if(!is_array($new_charts))
      return $this->_error('Field charts is not array');
    
    $save_counters = array();
    foreach($new_counters as $uid => $fields)
    {
      if(!isset($fields['title']) || !$fields['title'])
        return $this->_error('Bad counter title "'.$fields['title'].'"');
      $title = $fields['title'];
      $is_hidden = isset($fields['is_hidden']) ? 1 == $fields['is_hidden'] : false;
      if(!$title)
        return $this->_error('Bad title "'.$title.'"');
      if(!$uid || strlen($uid) > 25)
        return $this->_error('Bad uid "'.$uid.'"');
      if(!isset($by_uid[$uid]))
      {
        $counter = new Counter();
        $counter->set('project_id', $project->id);
        $counter->set('uid', $uid);
        $by_uid[$uid] = $counter;
      }
      $counter = $by_uid[$uid];
      if($counter->get('title') != $title)
        $counter->set('title', $title);  
      $is_hidden = $is_hidden ? 1 : 0;
      if($counter->get('is_hidden') != $is_hidden)
        $counter->set('is_hidden',  $is_hidden);
      $save_counters[$uid] = $counter;
    }
    
    foreach($save_counters as $counter)
    {
      if($counter->isDirty())
        $counter->saveSkipValidation();
    }
    foreach($by_uid as $uid => $counter)
      if(!isset($save_counters[$uid]) && $is_replace)
        $counter->destroy();

    $charts = array();
    foreach($project->get('charts') as $chart)
      $charts[$chart->get('uid')] = $chart;
    $save_charts = array();
    foreach($new_charts as $uid => $fields)
    {
      if(!isset($fields['title']) || !$fields['title'])
        return $this->_error('Bad chart title "'.$fields['title'].'"');
      if(!$uid || strlen($uid) > 25)
        return $this->_error('Bad chart uid "'.$uid.'"');
      if(!isset($charts[$uid]))                  
      {
        $chart = new Chart();
        $chart->set('project_id', $project->getId());
        $chart->set('uid', $uid);
        $charts[$uid] = $chart;
      }
      $chart = $charts[$uid];
      $chart->set('title', $fields['title']);
      if(!isset($fields['counters']) || !is_array($fields['counters']))
        return $this->_error('Bad chart counters: '.json_encode($fields['counters']).", uid: ".$uid.", title: ".$fields['title']);
      $counters = array();
      foreach(array_unique($fields['counters']) as $cuid)
      {
        if(!isset($save_counters[$cuid]))
          return $this->_error('Counter "'.$cuid.'" not found');
        $counters[] = $save_counters[$cuid];
      }
      $chart->set('counters', $counters);
      $chart->set('bc_eval', isset($fields['bc_eval']) ? $fields['bc_eval'] : '');

      if (isset($fields['eval']) && isset($fields['eval_counters']))
      {
        $eval_counters = array();
        foreach(array_unique($fields['eval_counters']) as $cuid)
        {
          if(!isset($save_counters[$cuid]))
            return $this->_error('Counter "'.$cuid.'" not found');
          $eval_counters[] = $save_counters[$cuid];
        }
        $chart->set('eval_counters', $eval_counters);
        $chart->set('eval', isset($fields['eval']) ? $fields['eval'] : '');
      }
      else
      {
        $chart->set('eval_counters', array());
        $chart->set('eval', '');
      }

      $chart->saveSkipValidation();
      $save_charts[$uid] = $chart;
    }
    foreach($charts as $uid => $chart)
      if(!isset($save_charts[$uid]) && $is_replace)
        $chart->destroy();

    $this->_success();
  }

  protected function _error($message)
  {
    $this->response->write(json_encode(array('error' => $message)));  
  }

  protected function _success()
  {
    $this->response->write(json_encode(array('success' => true)));
  }
}
