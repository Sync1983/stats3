<?php

class BaseLoginService
{
  protected $_member_id = null;
  protected $_sailt = "&f(921)^`\n";
  protected $_auth_token;

  function setSailt($sailt)
  {
    $this->_sailt = $sailt;
  }

  function resetMemberId()
  {
    $this->_member_id = null;
  }

  function getMemberIdNotSafe()
  {
    if($id = $this->getMemberId())
      return $id;
    if($this->_auth_token)
    {
      $id = explode(',', $this->_auth_token);
      $id = array_shift($id);
      if($id && is_numeric($id))
        return $id;
    }
    return null;
  }

  function getMemberId()
  {
    return $this->_member_id;
  }

  function setAuthToken($token)
  {
    if(null !== ($member_id = $this->getMemberIdForToken($token)))  
      $this->_member_id = $member_id;
    $this->_auth_token = $token;
  }

  function getMemberIdForToken($ticket)
  {
    $id = explode(',', $ticket);
    if(!$id || count($id) != 2)
      return null;
    list($id, $token) = $id;
    if(!$id || !is_numeric($id) || !$token || $token != $this->_getToken($id))
      return null;
    return $id;
  }
  
  function getAuthTokenForId($id)
  {
    $token = $this->_getSig($id, rand(0, 10000), microtime());
    $this->_setToken($id, $token);
    //bit_log_write('set token '.$token.' for '.$id, 'login');
    return implode(',', array($id, $token));
  }

  private function _getToken($id)
  {
    $cache = bit_memcache();
    if($cache)
    {
      $value = $cache->get('auth-'.$id);
      if($value && strlen($value) == 32)
        return $value;
    }
    $value = dbal()->fetchOneValue('SELECT token FROM member WHERE id='.intval($id));
    if($cache)
      $cache->set('auth-'.$id, $value, 0, 3600*8);
    return $value;
  }

  private function _setToken($id, $value)
  {
    if(strlen($value) != 32)
      throw new Exception('Bad token value: '.$value);
    dbal()->execute('UPDATE member SET token=\''.dbal()->escape($value).'\' WHERE id='.intval($id));
    $cache = bit_memcache();
    if($cache)
      $cache->set('auth-'.$id, $value, 0, 3600*8);
  }
  
  function getExternalIdByTicket($ticket)
  {
    if(!($id = $this->_decodeSecuryValue($ticket, 'ticket')))
      return null;
    return $id;
  }

  function getTicketByExternalId($id)
  {
    $expire = time() + 24*60*60;
    return implode(',', array($id, $expire, $this->_getSig($id, $expire, 'ticket')));  
  }

  protected function _decodeSecuryValue($session, $sailt)
  {
    if(!$session)
      return null;
    $session = explode(',', $session, 3);
    if(count($session) < 3)
      return null;
    list($id, $expire, $sig) = $session;
    if($expire < time() || !$id || $this->_getSig($id, $expire, $sailt) !== $sig)
      return null;
    return $id;
  }
  
  protected function _getSig()
  {
    $sig = func_get_args(); 
    array_unshift($sig, $this->_sailt);
    $sig = implode('||', $sig);
    return substr(md5($sig), 0, 10) . substr(sha1($sig), -22); 
  }
}
