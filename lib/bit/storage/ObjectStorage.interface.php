<?php

interface ObjectStorage
{
  function get($key);  
  function set($key, $value, $is_dirty = true);  
  function remove($key);
  function fetchOldRecords($utime, $limit = 10);
  function flush();
  static function prepare($conf);
}
