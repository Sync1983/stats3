<?php

class RedisLogger
{
  const REDIS_PREFIX = "stat_logger::";
  const MAP_PREFIX = "logger_map::";
  const HOST = 'localhost';
  const PORT = 6379;
  const TTL = 172800; //2 days
  const COUNTER = 'id_';
  /* @var Redis */
  private static $rd = null;
  private $counter_key = null;
  
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
  
  private function counterKey()
  {
    $date = date('d.m.Y')."::";
    if($this->counter_key==null)
      $this->counter_key = self::COUNTER.self::REDIS_PREFIX.$date;    
    $this->redis()->incr($this->counter_key);
    return self::REDIS_PREFIX.$date.$this->redis()->get($this->counter_key);            
  }
  
  public function saveObject($project_id,$data)
  {
    $timer = gme_pinba()->startTimer("Redis", "Redis_save_object");
    echo "save\r\n";
    $key = $this->counterKey();
    $data = $project_id."}".$data;
    $this->redis()->set($key, $data);    
    $this->redis()->expire($key,self::TTL);        
    gme_pinba()->stopTimer($timer);
  }
  
  public function getSavedObjects($limit, $undelite=false)
  {
    $keys = $this->redis()->keys(self::REDIS_PREFIX.'*');    
    if(count($keys)>$limit)
      $keys =  array_slice($keys, 0, $limit);
    $result = array();
    foreach ($keys as $key) {      
      $result[] = $this->redis()->get($key);
      if(!$undelite)
        $this->redis()->del($key);
    }
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
