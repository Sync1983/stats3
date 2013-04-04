<?php

class spController extends lmbController
{
  function getObjectByRequestedId($name, $field = 'id')
  {
    if(!$id = $this->request->get($field)) 
      return false;

    if(!($object = lmbActiveRecord::findById($name, (int) $id, false))) 
      return false;

    return $object;
  }

  function sendAjaxResponce($data = null, $add_view = false)
  {
    $responce = is_array($data) ? $data : array();
    if($add_view)
    {
      $js_callback = new lmbCollection();
      if($this->view)
        $responce['html'] = $this->renderTemplate($this->view, array('member' => $this->toolkit->getMember(), 'js_callback' => $js_callback));
      else
        $responce['html'] = '';
      $responce['js_callback'] = $js_callback->export();
    }
    $this->response->write(json_encode($responce, isset($_FILES) && count($_FILES) ? JSON_HEX_TAG : 0));
    $this->toolkit->setSkipViewRender(true);
  }

  function renderTemplate($template, $params = array())
  {
    if(is_string($template))
      $template = $this->toolkit->createViewByTemplate($template);    
    $template->set('request', $this->toolkit->getRequest());
    $template->set('session', $this->toolkit->getSession());
    $template->set('toolkit', $this->toolkit);    
    foreach($params as $name => $value)
      $template->set($name, $value);
    return $template->render();
  }

  function sendAjaxHtml()
  {
    return $this->sendAjaxResponce(null, true);  
  }

  function sendAjaxErrorList($error_list = null)
  {                           
    if(!$error_list)
      $error_list = $this->error_list;
    $errors = $error_list->export();
    if(count($errors) == 1)
      $errors = array_shift($errors)->message;
    else
    {
      $errors = implode("\n", $errors);  
      $errors = lmb_i18n('Следующие поля содержали ошибки:')."\n".$errors;
    }
    $this->sendAjaxError($errors);
  }

  function sendAjaxMessage($message)
  {
    return $this->sendAjaxValue('message', $message);
  }

  function sendAjaxError($error)
  {
    return $this->sendAjaxValue('error', $error);
  }

  function sendAjaxValue($name, $value)
  {
    return $this->sendAjaxResponce(array($name => $value));
  }

  function sendBadRequest()
  {
    if($this->toolkit->isAjaxRequest())
      $this->sendAjaxError(lmb_i18n("Неправильные параметры запроса"));
    else
    {
      $this->setTemplate($this->findTemplateByAlias('not_found'));
      $this->response->addHeader('HTTP/1.x 404 Not Found');
    }
  }

  function sendAjaxSuccess()
  {
    return $this->sendAjaxValue('success', 1);  
  }

  function isOnlyJsonResponse()
  {
    return substr(lmb_under_scores($this->getCurrentAction()), 0, 5) == 'ajax_';
  }

  function sendAjaxSlots($slots = null, $data = array()) 
  {
    if(is_null($slots))
      $slots = $this->_getDefaultAjaxSlots();
    elseif(!is_array($slots))
      $slots = array($slots);
    $proxy = new lmbObject(array('slots' => $slots));
    $this->view->set('render_ajax_proxy', $proxy);
    $this->view->set('toolkit', $this->toolkit);
    $this->view->render();
    $data['slots'] = $proxy->has('response') ? $proxy->get('response') : null;
    $this->sendAjaxResponce($data);
  }

  protected function _getDefaultAjaxSlots()
  {
    return $this->toolkit->getDefaultAjaxSlots();
  }

  function performAction()
  {
    if($this->is_forwarded)
      return false;
    
    if(method_exists($this, $method = $this->_mapCurrentActionToMethod()))
    {
      if($template_path = $this->findTemplateForAction($this->current_action))
        $this->setTemplate($template_path);
      $this->$method();
      $this->_passLocalAttributesToView();
      if(!$this->toolkit->skipViewRender() && $this->toolkit->isAjaxRequest())
        $this->sendAjaxSlots();
    }
    else
      return $this->sendBadRequest();
  }
}
