<?php

require_once('bit/game/HashMapFixedSize.class.php');

class HashMapFixedSizeTest extends UnitTestCase
{
  function testSpeed()
  {
    $profile = function($name = null)
    {
      static $time, $last_name, $mem;
      if($name)
      {
        $time = microtime(1);
        $last_name = $name;
        $mem = memory_get_usage();
      }
      else
        printf("%s: %.5fs %u\n", $last_name, microtime(1) - $time, memory_get_usage() - $mem);
    };

    $hash_map = new HashMapFixedSize(10);

    for($i = 0; $i < 100; $i++)
      $hash_map->set($i, $i);
    
    $this->assertEqual($hash_map->get(1), null);
    $this->assertEqual($hash_map->get(89), null);
    $this->assertEqual($hash_map->get(90), 90);
    $this->assertEqual($hash_map->get(99), 99);
  }
}
