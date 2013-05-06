<?php

class BitStatsApiClient
{
  const COUNTER_MAU = 'mau.stats';
  const COUNTER_DAU = 'dau.stats';

  private $url;
  private $key;

  function __construct($url, $key)
  {
    $this->url = $url;
    $this->key = $key;
  }

  function setCounters($counters, $charts)
  {
    $response = $this->_apiCall('set_counters', array('new_counters' => $counters, 'charts' => $charts));    
    return isset($response['success']) && $response['success'] == true;
  }
  
  function addCounters($counters, $charts)
  {
    $response = $this->_apiCall('add_counters', array('new_counters' => $counters, 'charts' => $charts));    
    return isset($response['success']) && $response['success'] == true;
  }

  function setCounterValue($date, $name, $value)
  {
    $response = $this->_apiCall('set_counter_value', array('date' => $date, 'name' => $name, 'value' => $value));    
    return isset($response['success']) && $response['success'] == true;
  }

  // $referers: array('uid' => 'title')
  function addReferers($referers)
  {
    $response = $this->_apiCall('add_referers', array('referers' => $referers));    
    return isset($response['success']) && $response['success'] == true;
  }
  
  function getReferers()
  {
    return $this->_apiCall('get_referrers');    
  }

  function fetchStats($date, $fields)
  {
    return $this->_apiCall('fetch_stats', array('date' => $date, 'fields' => $fields));
  }
  
  function setCountersValues($date, $values)
  {
    $response = $this->_apiCall('set_counters_values', array('date' => $date, 'values' => $values));
    return isset($response['success']) && $response['success'] == true;
  }
  
  protected function _apiCall($method, $params = array())
  {
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $this->url . '/api/'.$method);
	  curl_setopt($ch, CURLOPT_VERBOSE, 0); // выводит информацию о состоянии curl запроса
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	  
    $params['key'] = $this->key;

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    
	  $response_text = curl_exec($ch);

    $error_no = curl_errno($ch);
    $error_str = curl_error($ch);
    curl_close($ch);

    if($error_no) 
      throw new Exception("Curl error: ".$error_str); 
    $response_vars = @json_decode($response_text, true);
    if(!is_array($response_vars))
      throw new Exception('Failed parse response: '.$response_text);
    if(array_key_exists('error', $response_vars))
      throw new Exception('Api error: '.$response_vars['error']);
    return $response_vars;
  }
}
