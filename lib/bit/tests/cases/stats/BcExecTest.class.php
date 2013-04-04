<?php

class BcExexTest extends UnitTestCase
{
  function test()
  {
    $vars = array(
      'x' => '2 + 76*2',
      'y' => 'is bad',
      'z' => '2/0',
      'a' => '8 ^ 2'
    );

    $vars = bc_exec($vars, 0);
    $this->assertEqual($vars['x'], 154);
    $this->assertEqual($vars['y'], 0);
    $this->assertEqual($vars['z'], 0);
    $this->assertEqual($vars['a'], 64);
  }
}
