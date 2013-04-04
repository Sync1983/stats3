<?php

require_once('bit/stats/common.inc.php');

class DbaWrapperTest extends UnitTestCase
{
  function testFetch()
  {
    $db_file = tempnam(test_stats('varDir'), 'dba');
    @unlink($db_file);
    
    $db = new DbaWrapper($db_file);

    $this->assertFalse($db->get('hello'));

    @unlink($db_file);
  }
}
