<?php

require_once('bit/stats/common.inc.php');

class LogReaderTests extends UnitTestCase
{
  function testPosition()
  {
    $db_file = tempnam(test_stats('varDir'), 'dba');
    $log_file = tempnam(test_stats('varDir'), 'log');
    @unlink($db_file);
    @unlink($log_file);
    
    $db = new DbaWrapper($db_file);
    for($i = 0; $i < 100; $i++)
      file_put_contents($log_file, $i."\n", FILE_APPEND);

    $log_reader = new StastLogReader($db, $log_file);

    $this->assertEqual("0", $log_reader->readNextLine());
    $this->assertEqual("1", $log_reader->readNextLine());
    $this->assertEqual("2", $log_reader->readNextLine());

    unset($db, $log_reader);

    $db = new DbaWrapper($db_file);
    $log_reader = new StastLogReader($db, $log_file);
    $this->assertEqual("3", $log_reader->readNextLine());
    
    unset($db, $log_reader);

    @unlink($db_file);
    @unlink($log_file);
  }
}
