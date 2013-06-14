<?php
  $key = "1pjdcvc3f5qqbj28makku37i00oa2ler";
  $url = "stats2.sync.bit";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url . '/api/get_stamp');
  curl_setopt($ch, CURLOPT_VERBOSE, 0); // Ð²Ñ‹Ð²Ð¾Ð´Ð¸Ñ‚ Ð¸Ð½Ñ„Ð¾Ñ€Ð¼Ð°Ñ†Ð¸ÑŽ Ð¾ ÑÐ¾ÑÑ‚Ð¾ÑÐ½Ð¸Ð¸ curl Ð·Ð°Ð¿Ñ€Ð¾ÑÐ°  
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 36000);

  //$params['key'] = $key;
  //$params['map'] = json_encode($map);

  //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
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
  
  echo $response_text;
