<?php

class DbTest extends UnitTestCase
{
  function testQuery()
  {
    try
    {
      dbal()->execute('SElewe');
      $this->assertFalse(true);
    } catch (Exception $e) {
      $this->assertTrue(true);
    }
  }

  function testActiveRecord()
  {
    dbal()->execute('DROP TABLE IF EXISTS dbal_active_record_test;');
    dbal()->execute('CREATE TABLE  dbal_active_record_test (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `member_id` int(10) unsigned NOT NULL DEFAULT \'0\',
      `title` varchar(255) NOT NULL DEFAULT \'\',
      `x` smallint(6) NOT NULL DEFAULT \'0\',
      `y` smallint(6) NOT NULL DEFAULT \'0\',
      `type` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
      PRIMARY KEY (`id`),
      KEY `member` (`member_id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1');

    $ar = new DbTest_ActiveRecord(10);
    $this->assertFalse($ar->tryLoad());
    $this->assertFalse($ar->save());

    $ar = new DbTest_ActiveRecord();
    $ar->set('member_id', 90);
    $ar->set('title', "') SQL Injection! \0");
    $ar->set('x', 124);
    $ar->set('xx', 'watch it?');

    $this->assertTrue($ar->save());
    $this->assertTrue($ar->getId());

    $ar_loaded = new DbTest_ActiveRecord($ar->getId());
    $this->assertTrue($ar_loaded->tryLoad());
    $this->assertEqual($ar_loaded->getId(), $ar->getId());
    $this->assertEqual($ar_loaded->get('title'), $ar->get('title'));
    $this->assertEqual($ar_loaded->get('x'), $ar->get('x'));
    $this->assertEqual($ar_loaded->get('member_id'), $ar->get('member_id'));

    $this->assertFalse($ar_loaded->get('xx'));
    dbal()->execute('DROP TABLE IF EXISTS dbal_active_record_test;');
  }

  function testType()
  {
    dbal()->execute('DROP TABLE IF EXISTS dbal_types_test;');
    dbal()->execute('CREATE TABLE  dbal_types_test (
      `tbyte` tinyint NOT NULL DEFAULT \'0\',
      `tsmall` smallint unsigned NOT NULL,
      `tmint` mediumint unsigned NOT NULL,
      `tint` int unsigned NOT NULL DEFAULT \'0\',
      `sint` int NOT NULL DEFAULT \'0\',
      `tlong` bigint unsigned NOT NULL DEFAULT \'0\',
      `slong` bigint NOT NULL DEFAULT \'0\',
      `tstring` CHAR(100) NOT NULL DEFAULT \'\'
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1');

    dbal()->execute('INSERT INTO dbal_types_test '.
      '(tbyte, tsmall, tmint, tint, sint, tlong, slong, tstring) VALUES '.
      '(12, 32000, 3000000, 2000000000, -2000000000, 8000000000, -4000000000, \'is_smail\'),'.
      '(12, 32000, 3000000, 4000000000, -2000000000, 18446744073709551615, -1, \'is_big\')'
    );

    $rows = dbal()->fetch('SELECT * FROM dbal_types_test WHERE tstring=\'is_smail\'');
    $this->assertEqual(count($rows), 1);
    $row = $rows[0];
    $this->assertTrue(12 === $row['tbyte']);
    $this->assertTrue(32000 === $row['tsmall']);
    $this->assertTrue(3000000 === $row['tmint']);
    $this->assertTrue(2000000000 === $row['tint']);
    $this->assertTrue(-2000000000 === $row['sint']);
    if(PHP_INT_MAX > 8000000000)
    {
      $this->assertTrue(8000000000 === $row['tlong']);
      $this->assertTrue(is_int($row['tlong']));
      $this->assertTrue(is_int($row['slong']));
    }
    else
    {
      $this->assertTrue('8000000000' === $row['tlong']);
      $this->assertTrue(is_string($row['tlong']));
      $this->assertTrue(is_string($row['slong']));
    }
    $this->assertTrue('is_smail' === $row['tstring']);
    
    $rows = dbal()->fetch('SELECT * FROM dbal_types_test WHERE tstring=\'is_big\'');
    $row = $rows[0];
    $this->assertTrue('18446744073709551615' === $row['tlong']);
    $this->assertTrue(is_string($row['tlong']));
    
    dbal()->execute('DROP TABLE IF EXISTS dbal_types_test;');
  }
}

class DbTest_ActiveRecord extends ActiveRecord
{
  protected $_table_name = 'dbal_active_record_test';
  protected $_db_fields = array('member_id', 'title', 'x', 'y', 'type');
}
