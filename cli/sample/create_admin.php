<?php

require_once(__DIR__ . '/../../setup.php');

if(!$member = Member :: findByLogin('admin'))
{
  $member = new Member();
  $member->set('login', 'admin');
  $member->set('hashed_password', $member->cryptPassword('test'));
  $member->saveSkipValidation();
}
