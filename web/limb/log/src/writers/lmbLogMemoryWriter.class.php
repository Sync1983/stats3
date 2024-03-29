<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com
 * @copyright  Copyright &copy; 2004-2009 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html
 */
lmb_require('limb/log/src/lmbLogWriter.interface.php');

/**
 * class lmbLogMemoryWriter.
 *
 * @package log
 * @version $Id$
 */
class lmbLogMemoryWriter implements lmbLogWriter
{
  protected $entries = array();

  function __construct(lmbUri $dsn) {}

  function write(lmbLogEntry $entry)
  {
    $this->entries[] = $entry;
  }

  function getEntries()
  {
    return $this->entries;
  }
}
