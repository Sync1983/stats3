<?php

class RedisLogger
{
  const REDIS_PREFIX = "stat_logger::";
  const MAP_PREFIX = "logger_map::";
  const HOST = 'localhost';
  const PORT = 6379;    
  /* @var Redis */
  private static $rd = null;  
  
  function __construct() {
    if(self::$rd==null)
    {
      self::$rd = new Redis();
      self::$rd->connect(self::HOST, self::PORT);            
    }
  }
  /* *
  * @return Redis
  */
  public function redis()
  {
    if(self::$rd==null)
    {
      self::$rd = new Redis();
      self::$rd->connect(self::HOST, self::PORT);      
    }
    return self::$rd;
  }
  
  public function getSavedObjects($limit, $delete=true)
  {
    $key = "stat_logger::events";
    $result = $this->redis()->lrange($key,0,$limit);
    $count = count($result);
    if($delete)
      $this->redis()->ltrim(0,$count);
    return $result;
  }  
  
  public function map_flush()
  {
    
  }
  
  public function map_add($name,$params,$project_id = null)
  {
    if($project_id)
      $name = $project_id."::".$name;
    $this->redis()->hmSet(self::MAP_PREFIX.$name,$params);
  }
  
  public  function map_get($name,$field,$project_id = null)
  {
    if($project_id)
      $name = $project_id."::".$name;
    return $this->redis()->hget(self::MAP_PREFIX.$name,$field);
  }
  
  public  function map_names()
  {
    return $this->redis()->keys(self::MAP_PREFIX.'*');
  }
}

?>
