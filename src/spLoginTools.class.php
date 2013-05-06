<?php

class spLoginTools extends spTools
{
  const COOKIE_MEMBER_ID = 'member_id';
  const COOKIE_MEMBER_HASH = 'member_hash';
  
  protected $member;
  
  function setMember($member)
  {
    $this->member = $member;
  }

  function setLoggedInMember($member)
  {
    $member->set('is_logged_in', true);  
    $this->setMember($member);
  }
  
  function getMember()
  {
    if(!is_object($this->member)) {
      $this->member = $this->_getMemberByCookies();
      if(!$this->member) {
        $this->member = new Member();
        $this->member->set('is_logged_in', false);
      }
      else
        $this->member->set('is_logged_in', true);
    }
    return $this->member;
  }

  function setAutologinCookie($member)
  {
    $response = $this->toolkit->getResponse();
    $response->setCookie(self :: COOKIE_MEMBER_ID, $member->id, time() + 24 * 60 * 60 * 30, '/'.lmb_env_get('LIMB_HTTP_OFFSET_PATH'), null, false, true);    
    $response->setCookie(self :: COOKIE_MEMBER_HASH, $member->getAutologinHash(), time() + 24 * 60 * 60 * 30, '/'.lmb_env_get('LIMB_HTTP_OFFSET_PATH'), null, false, true);
  }
  
  function removeAutoLoginCookie()
  {
    $response = $this->toolkit->getResponse();
    $response->setCookie(self :: COOKIE_MEMBER_ID, 0, time() - 1, '/'.lmb_env_get('LIMB_HTTP_OFFSET_PATH'), null, false, true);
    $response->setCookie(self :: COOKIE_MEMBER_HASH, 0, time() - 1, '/'.lmb_env_get('LIMB_HTTP_OFFSET_PATH'), null, false, true);
  }
  
  protected function _getAutologinCookie()
  {
    return array(
      $this->toolkit->request->getCookie(self :: COOKIE_MEMBER_ID), 
      $this->toolkit->request->getCookie(self :: COOKIE_MEMBER_HASH)
    );  
  }

  protected function _getMemberByCookies()
  {
    list($id, $hash) = $this->_getAutologinCookie();
    if(!$id || !$hash)
      return false;
    $member = lmbActiveRecord :: findById('Member', $id, false);
    if($member && $member->getAutologinHash() === $hash)
      return $member;
    $this->removeAutoLoginCookie();
    return false;
  }
}
