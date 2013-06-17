<?php

class Member extends lmbActiveRecord
{
  public function isLoggedIn() {    
    return $this->getId() && $this->_getRaw('is_logged_in');
  }
  
  public function isAdmin() {
    return $this->_getRaw('is_admin');
  }
  
  public function getProjectAccessIds() {
    if(!$this->isAdmin()) {
      $ids = $this->get('projects_ids');
      return $ids ? unpack('V*', $ids) : array();
    }
    $sql_projects = self::find('Project');
    $ids = array();
    foreach ($sql_projects as $project)
      $ids[] = $project['id'];
    return $ids;
  }
  
  protected function _createValidator()
  {
    //lmb_require('src/validation/MemberUniqueFieldRule.class.php');
    $validator = parent :: _createValidator();
    $validator->addRequiredRule('login', lmb_i18n('Логин обязательное поле'));
    //$validator->addRule(new MemberUniqueFieldRule('login', $this, lmb_i18n('Этот логин уже занят')));
    return $validator;
  }
  
  public function getAutologinHash() {
    $sailt = "^&*\t\n@,mg'я" . $this->get('auto_login_sailt') . ':' . lmbToolkit :: instance()->getEncodeRealIp();    
    return substr(sha1($this->hashed_password . $sailt), 0, 14) . substr(md5($this->id . $sailt), 0, 18); 
  }
  
  static function cryptPassword($password) {
    $sailt1 = "s(*&&n3яч"; $sailt2 = "LrfЛцЗ";
    return substr(md5($password . $sailt1), 5) . substr(sha1($password . $sailt2), 3, 20);
  }
  
  static function findByLogin($login) {
    if($login)
      return lmbActiveRecord :: findOne('Member', array('login=?', $login));
  }

  static function findByLoginAndPassword($login, $password) {    
    $member = self :: findByLogin($login);    
    return ($member && $member->isValidPassword($password)) ? $member : false;
  }

  private function isValidPassword($password) {
    return $this->hashed_password === self::cryptPassword($password);
  }

  function setProjectAccessIdsView($value)
  {
    $ids = '';
    $value = array_unique(array_map('intval', array_map('trim', explode(',', $value))));  
    if($value)
      foreach($value as $id)
        if($id)
          $ids .= pack('V', $id);
    $this->set('projects_ids', $ids);
  }
  
}
