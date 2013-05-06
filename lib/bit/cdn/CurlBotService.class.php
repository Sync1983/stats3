<?php

class CurlBotService 
{
  public $content_encoding;
  protected $current_xpather;
  protected $cache_enable;
  protected $curls = array();
  protected $cookies = true;
  protected $cookies_file;
  protected $proxy;
  protected $user_agent;
  protected $_last_req_time = 0;
  public $connection_timeout = 10;
  public $remove_cookies = true;
  public $replace_head = true;
  public $crawl_delay = 0;
  
  protected function _reqPause()
  {
    $t = $this->crawl_delay - (time() - $this->_last_req_time);
    if($t > 0)
    {
      //echo "sleep {$t}\n";
      sleep($t);
    }
    $this->_last_req_time = time();
  }

  function __construct($var_dir, $content_encoding = false)
  {
    $cookie_dir = $var_dir . '/curl_cookie';
    @mkdir($cookie_dir);

    $this->cookies_file = $cookie_dir . '/cookies.curl'.abs(crc32(microtime()));
    file_put_contents($this->cookies_file, '');
    $this->cookies_file = realpath($this->cookies_file);

    $this->cache_dir = $var_dir . '/curl_cache';
    @mkdir($this->cache_dir);
    $this->content_encoding = strtolower($content_encoding);
    $this->user_agent = $this->getRandUserAgent();
  }

  protected function _createCurl()
  {
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_TIMEOUT, $this->connection_timeout);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connection_timeout);
    curl_setopt($curl, CURLOPT_USERAGENT, $this->user_agent); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
	  curl_setopt($curl, CURLOPT_VERBOSE, 0); // выводит информацию о состоянии curl запроса
    return $curl;
  }

  function getCookiesFile()
  {
    return $this->cookies_file;  
  }

  function setCookies($cookie)
  {
    $this->cookies = $cookie;    
  }

  function setProxy($host, $port = false)
  {
    if(is_array($host))
      $this->proxy = $host;
    else
      $this->proxy = array($host, $port);
  }

  function enableCache()
  {
    $this->cache_enable = true; 
  }

  function disableCache()
  {
    $this->cache_enable = false; 
  }

  function get($url, $post = false, $headers = false, $cache_enable = false)
  {
    //echo "GET ".$url."\n";
    if($cache_enable)
      $cache = $this->cache_dir . '/' . md5($url).md5(serialize($post));
    if($cache_enable && file_exists($cache))
      return file_get_contents($cache);
    if(false === ($content = $this->rawGet($url, $post, $headers)))
      return false;
    if($cache_enable)
      file_put_contents($cache, $content);
    return $content;
  }
  
  function rawGet($url, $post = false, $headers = false, &$curl = false)
  {
    $curl = $this->prepareCurl(false, $url, $post, $headers);
    $this->_reqPause();
    //echo "Send request ...";
    $content = curl_exec($curl);
    
    if($this->content_encoding && $this->content_encoding !== 'utf-8')
      $content = mb_convert_encoding($content, 'utf-8', $this->content_encoding);
    //echo " [ok]\n";
    if(200 != curl_getinfo($curl, CURLINFO_HTTP_CODE))
      return false;
    //curl_close($curl);
    return $content;
  }

  function prepareCurl($clone, $url, $post, $headers)
  {
    $curl = $this->_createCurl();
    curl_setopt($curl, CURLOPT_URL, $url);
    if($post)
    {
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }
    else
      curl_setopt($curl, CURLOPT_POST, false);

    if($headers)
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    if($this->cookies)
    {
      curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookies_file);   
      curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookies_file);   
    }

    if($this->proxy)
    {
      curl_setopt($curl, CURLOPT_PROXY, $this->proxy[0]);
      curl_setopt($curl, CURLOPT_PROXYPORT, $this->proxy[1]);
    }

    return $curl;
  }

  function multiGet($curls, $encoding = false)
  {
    if(!$encoding)
      $encoding = $this->content_encoding;
    $mh = curl_multi_init();
    foreach($curls as $curl)
      curl_multi_add_handle($mh, $curl);
    $running = null;
    $old_running = 0;
    $this->_reqPause();
    do
    {
      curl_multi_exec($mh, $running);
      if($old_running !== $running && $running)
      {
        //echo "Running {$running}\n";
      }
      $old_running = $running;
    }
    while($running > 0);
    $results = array();
    foreach($curls as $id => $curl)
    {
      $results[$id] = curl_multi_getcontent($curl);
      if($encoding && $encoding !== 'utf-8')
        $results[$id] = mb_convert_encoding($results[$id], 'utf-8', $encoding);
      curl_multi_remove_handle($mh, $curl);
      curl_close($curl);
    }
    curl_multi_close($mh);
    return $results;
  }

  function getDomXpath($url, $cache_enable = false, $trys = 1)
  {
    if(!$dom = $this->getDomElement($url, $cache_enable, $trys))
      throw new Exception('Failed get DomDocument!');
    return new DOMXPath($dom);
  }

  function getDomElement($url, $cache_enable = false, $trys = 1)
  {                                 
    return $this->createDomElement($this->tryGet($url, $trys, $cache_enable));
  } 

  function tryGet($url, $trys, $cache_enable)
  {
    $try = 0;
    while($try++ < $trys && !($content = $this->get($url, false, false, $cache_enable)))
    {
      //echo "Try {$try}\n";
    }
    if($content === false)
      throw new Exception('Failed get content! URL: '.$url);
    return $content;
  }

  function getCurl()
  {
    return curl_copy_handle($this->curls[array_rand($this->curls)]);
  }

  function createDomElement($html)
  {
    if($html === false)
      return false;
    if($this->replace_head)
    {
      $html = preg_replace(
        array(
          '/<meta[^>]+?http-equiv\s*?=\s*?[\'"]content-type[\'"][^>]+?'.'>/ium',
          '/<noscript>.*?<\/noscript>/ium'
        ), 
        array('', ''), 
        $html
      );                             
      $valid_head = '<head><meta http-equiv="content-type" content="text/html; charset=utf-8">';
      $html = str_replace('<head>', $valid_head, $html);
      if(strpos($html, '<head>') === false)
        $html = $valid_head . '</head>' . $html; 
    }

    $document = new DOMDocument('1.0', 'UTF-8'); 
    if(!(@$document->loadHTML($html)))
      throw new Exception('Html content not valid!');
    return $document;
  }

  function setCurrentDom($dom)
  {
    $this->current_xpather = new DOMXPath($dom);
  }

  function safeXpath($xpath, $raw = false)
  {
    $res = $this->current_xpather->query($xpath); 
    if(!$res->length)
      return false;
    if($raw)
      return $res;
    $res = $res->item(0);
    if(preg_match('~/@\w+$~iu', $xpath))
      return $res->value;
    return '' . simplexml_import_dom($res)->asXml();
  }

  function __destruct()
  {
    foreach($this->curls as $curl)
      if(is_resource($curl))
        curl_close($curl);
    if($this->remove_cookies)
      $this->removeCookiesFile();
  }

  function removeCookiesFile()
  {
    if($this->cookies_file)
      @unlink($this->cookies_file);
  }

  function getCookie($name, $domain)
  {
    $cookies = file_get_contents($this->cookies_file);
    foreach(explode("\n", $cookies) as $line)
    {
      $line = trim($line);
      if(!$line || $line[0] == '#')
        continue;
      $data = preg_split('~\s~', $line);
      if($data[0] != $domain)
        continue;
      if($data[6] == $name)
        return urldecode($data[7]);
    }
  }

  function getRandUserAgent()
  {
    $ua = array(
      'Mozilla/5.0 (Windows; U; Windows NT 5.1; ca; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4 (.NET CLR 3.5.30729)',
      'Mozilla/5.0 (Windows; U; Windows NT 5.0; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3',
      'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; MRA 5.5 (build 02842); MRSPUTNIK 2, 1, 0, 4 SW)',
      'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 1.1.4322; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)',
      'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
      'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; GTB5; MRSPUTNIK 2, 0, 1, 90 SW; MRA 5.5 (build 02842); SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30618)',
      'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
      'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; GTB6; MRA 5.5 (build 02761); MRSPUTNIK 2, 1, 0, 4 SW; MRA 5.5 (build 02761); SLCC1; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30618)',
      'Mozilla/4.5b1 [en] (X11; I; Linux 2.0.35 i586)',
      'Opera/9.23 (Windows NT 5.1; U; ru)',
      'Opera/9.64 (Windows NT 5.1; U; MRA 5.5 (build 02842); ru)',
      'Opera/9.64 (Windows NT 5.1; U; ru) Presto/2.1.1',
    );
    return $ua[array_rand($ua)];
  }
}
