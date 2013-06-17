<?php

class LoginController extends spController
{
  function doDisplay()
  {
    //echo $this->renderTemplate('login/display.phtml');    
  }

  function doAjaxLogin()
  {
    if(!$this->request->hasPost())
      return;
    
    $this->validator->addRequiredRule('password', lmb_i18n('Введите пароль'));
    $this->validator->addRequiredRule('login', lmb_i18n('Введите логин'));    
    $this->validate($this->request);
    $remember = isset($this->request['remember'])?($this->request['remember']=="checked"):false;
    
    if(!$this->error_list->isEmpty())
      return $this->sendAjaxErrorList();

    $member = Member :: findByLoginAndPassword($this->request->get('login'), $this->request->get('password'));
    if(!$member)
      return $this->sendAjaxError(lmb_i18n('Неправильный логин или пароль!'));
    
    $member->set('auto_login_sailt', $this->toolkit->getEncodeRealIp() . ':' . mt_rand(0, 2000000000));    
    $member->saveSkipValidation();
      
    $this->toolkit->setLoggedInMember($member);
    $this->toolkit->setAutologinCookie($member,$remember);
    
    $this->sendAjaxResponce(array('redirect' => '?'),true);
  }

  function doLogout()
  {
    $this->toolkit->removeAutoLoginCookie();
    if(($member = $this->toolkit->getMember()) && $member->isLoggedIn())
    {
      $member->set('auto_login_sailt', '');
      $member->saveSkipValidation();
    }
    $this->redirect('/');
  }
}
