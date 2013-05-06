<?php

/**
 * class DateToMdyFormatFilter
 *
 * @filter date_mdy
 * @version $Id$
 */

class DateToMdyFormatFilter extends lmbMacroFunctionBasedFilter
{
  protected $function = 'date_to_mdy';
  protected $include_file = 'src/macro/filters.inc.php';
}
