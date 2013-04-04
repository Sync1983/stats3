<?php

require_once 'SabreAMF/DetailException.php';
require_once 'SabreAMF/CallbackServer.php';

function sabre_amf_run()
{
  SabreAMF_ClassMapper :: $maps['Error'] = 'AmfFlexError';

  $server = new SabreAMF_CallbackServer();
  $server->onInvokeService = 'amf_run_task';
  $server->exec();
}

function amf_tasks_history($add = null)
{
  static $history = array();
  if(null !== $add)
    $history[] = $add;
  else
    return $history;
}

function amf_post_json()
{
  $profile_id = bit_profile_log('amf request');
  try
  {
    $request = @json_decode(@gzinflate(file_get_contents('php://input')));
    if(!is_object($request))
      throw new Exception('Request is not object! '.substr(file_get_contents('php://input'), 0, 100));
    $response = amf_run_task($request->c, $request->m, $request->a);
    echo bson_encode(array('r' => $response));
  } catch(Exception $e) {
    if($e instanceof SabreAMF_DetailException)
      echo bson_encode(array('e' => $e->getMessage()));
    else
    {
      echo bson_encode(array('e' => 'Server error')); 
      bit_error_guard($e);
    }
  }
  bit_profile_log($profile_id, implode(',', amf_tasks_history()));
}

function amf_run_task($task_class, $method, $data)
{
  static $tasks = array();

  if(!is_array($data))
    throw new AmfMessageException('Argument is not array');

  if(!array_key_exists($task_class, $tasks))
  {
    $task_paths = bit_conf('amf_tasks_lazy_paths');
    if(strpos($task_class, "\0") !== false || !array_key_exists($task_class, $task_paths))
      return amf_assert(false, 'Task "'.$task_class.'" not found');
    require_once($task_paths[$task_class]);
    $task = new $task_class;
    $tasks[$task_class] = $task;
  }
  else
    $task = $tasks[$task_class];

  amf_tasks_history($task_class.'.'.$method);
  
  amf_assert(method_exists($task, $method), 'Method "'.$method.'" not exists in task '.$task_class);
  
  login_service()->setAuthToken($data ? array_shift($data) : '');
  try {
    if($task instanceof AmfTaskRequireMember)
      amf_assert($member_id = login_service()->getMemberId(), 'Authorize failed.');
    return call_user_func_array(array($task, $method), $data);
  } catch(Exception $e) {
    if(!($e instanceof AmfMessageExceptionInterface))
    {
      bit_error_guard($e);
      $e = new AmfMessageException('Exception');
    }
    else
      bit_log_write(sprintf(
        '[AMF] %s->%s member:%d %s %s', 
        $task_class, 
        $method, 
        intval(login_service()->getMemberIdNotSafe()), 
        $e->getMessage(), 
        json_encode($data)));
    throw new AmfSabreException($e->getMessage(), 'Method '.$task_class.'.'.$method);
  };
}

function amf_assert($assertion, $message = null)
{
  if(!$assertion)
    throw new AmfMessageException($message ? $message : 'Assert failed');
}

class AmfSabreException extends Exception implements SabreAMF_DetailException
{
  protected $detail;

  function __construct($message, $detail)
  {
    $this->detail = $detail;
    parent :: __construct($message);
  }

  function getDetail()
  {
    return $this->detail;
  }
};

class AmfMessageException extends Exception implements AmfMessageExceptionInterface
{
};

interface AmfMessageExceptionInterface 
{
  function getMessage();  
};

interface AmfTaskRequireMember {};

$conf = bit_conf();
if(!isset($conf->amf_tasks_lazy_paths))
  $conf->amf_tasks_lazy_paths = array();


