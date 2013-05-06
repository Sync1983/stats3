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
    echo "save\r\n";
    $key = $this->counterKey();
    $data = $project_id."}".$data;
    $this->redis()->set($key, $data);    
    $this->redis()->expire($key,self::TTL);        
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
  /*
   * 
   */
  public function map_add($name,$params)
  {
    $this->redis()->hmSet(self::MAP_PREFIX.$name,$params);
  }
  
  public  function map_get($name,$field)
  {
    return $this->redis()->hget(self::MAP_PREFIX.$name,$field);
  }
  
  public  function map_names()
  {
    return $this->redis()->keys(self::MAP_PREFIX.'*');
  }
}

$links = array(
    'addStock'    => 'log_addStock',
    'costStock'   => 'log_costStock',
    'featureUse'  => 'log_FeatureUse',
    'levelUp'     => 'log_LevelUp',
    'Login'       => 'log_Login',
    'NewPlayer'   => 'log_NewPlayer',
    'OutEnergy'   => 'log_OutEnergy',
    'payCost'     => 'log_payCost',
    'QuestDone'   => 'log_QuestDone',
    'QuestStart'  => 'log_QuestStart',
    'QuestTaskComplete' => 'log_QuestTaskComplete',
    'viralRecive' => 'log_viralRecive',
    'viralSend'   => 'log_viralSend',
);

if(file_exists(__DIR__.'/EnumLoggerEvent.class.php'))
  require_once(__DIR__.'/EnumLoggerEvent.class.php');

function cronWorker()
{
  global $links;
  $redis = new RedisLogger();
  $events = $redis->getSavedObjects(10000);
  $counter = 0;
  foreach ($events as $event)
  {
    $pos = strpos("}", $event);
    $project_id = substr($event, 0, $pos+1);
    $event = substr($event, $pos+2,  strlen($event)-$pos);    
    $decode = json_decode($event);      
    $event_id = $decode->event*1;
    $event_name = EnumLoggerEvent::getNameByValue($event_id);
    $counter++;
    if(isset($links[$event_name]))
      $logger = new $links[$event_name]();
    else
      $logger = new Logger();
    try{
    $logger->addItem($project_id, $decode);
    }  catch (Exception $E)
    {
      echo "Logger Add Event exception: ".$E->getMessage()."\r\n";
    }
  }
  echo "Inserts count: ".$counter."\r\n";
}

?>
