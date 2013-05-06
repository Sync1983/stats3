<?php

class NarodUploader
{
  private $_curl;
  private $_login;
  private $_passwd;
  private $_var_dir;

  protected function __construct($login, $passwd, $var_dir)
  {
    $this->_curl = null;  
    $this->_login = $login;  
    $this->_passwd = $passwd;  
    $this->_var_dir = $var_dir;  
  }

  function mkdir($path)
  {
    $path = '/' . trim($path, '/');
    $path = explode('/', $path);
    $title = array_pop($path);
    $path = trim(implode('/', $path), '/');
    if($path)
      $path = $path . '/';
    return $this->_doAction(array(
     'action' => 'create_directory',
     'path' => $path,
     'rnd' => $this->_rnd(),
     'title' => $title,
    ));
  }
  
  function ls($path)
  {
    $this->_getCurl()->rawGet('http://'.$this->_login.'.narod2.yandex.ru/filemanager/');
    $html = $this->_doAction(array('p' => 1), array(
     'action' => 'get_files',
     'path' => trim($path, '/') . '/',
     'rnd' => $this->_rnd(),
    ));
    $this->_getCurl()->setCurrentDom($this->_getCurl()->createDomElement($html));
    $files = array();
    foreach($this->_getCurl()->safeXpath('//tr[@class="b-files"]/td[@class="name"]/a/@href', true) as $href)
      $files[] = strval($href->value);
    return $files;
  }

  function upload($path, $file)
  {
    $path = str_replace('//', '/', '/'.trim($path, '/').'/');  
    $file = realpath($file);    

    $url = $this->_checkFileUrl($path, $file, false);
    if(null !== $url)
      return $url;

    $post = array(
      'file' => '@'.basename($file),
      'action' => 'upload',
      'currentDir' => $path,
    );
    $chdir = getcwd();
    chdir(dirname($file));
    $response = $this->_getCurl()->rawGet(
      'http://'.$this->_login.'.narod2.yandex.ru/filemanager/',
      $post,
      array(
        'Referer: http://'.$this->_login.'.narod2.yandex.ru/filemanager/',
      )
    );
    chdir($chdir);

    return $this->_checkFileUrl($path, $file, true);
  }

  private function _checkFileUrl($path, $file, $throw = false)
  {
    $url = 'http://'.$this->_login.'.narod2.ru/'.trim($path, '/').'/'.basename($file);
    if(md5(@file_get_contents($url)) == md5(file_get_contents($file)))
      return $url;
    if($throw)
      throw new Exception('Failed upload file. MD5 sum not valid '.$url);
    return null;
  }

  private function _rnd()
  {
    return rand(100000, 2000000) . rand(1000, 1000000);
  }

  private function _doAction($post, $get = false)
  {
    return $this->_getCurl()->rawGet(
      'http://'.$this->_login.'.narod2.yandex.ru/filemanager/' . ($get ? '?'.http_build_query($get) : ''),
      $post,
      array(
        'X-Requested-With: XMLHttpRequest',
        'Referer: http://'.$this->_login.'.narod2.yandex.ru/filemanager/',
      )
    );
  }

  private function _getCurl()
  {
    if(null == $this->_curl)
    {
      $curl = new CurlBotService($this->_var_dir);
      $login_url = 'http://passport.yandex.ru/passport?mode=auth&from=narod&retpath=http%3A%2F%2Fnarod.yandex.ru%2F';
      $post = array(
        'login' => $this->_login,
        'passwd' => $this->_passwd,
        'timestamp' => time() . '' . rand(100, 999),
      );
      $curl->rawGet($login_url, $post);
      $this->_curl = $curl;
    }
    return $this->_curl;
  }

  static function createByConf($conf)
  {
    return self :: login($conf['login'], $conf['passwd'], $conf['var_dir']);
  }

  static function login($login, $passwd, $var_dir)
  {
    return new NarodUploader($login, $passwd, $var_dir);
  }
}
