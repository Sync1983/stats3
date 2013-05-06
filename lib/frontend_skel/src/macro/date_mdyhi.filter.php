<?php

/**
 * class DateToMdyhiFormatFilter
 *
 * @filter date_mdyhi
 * @version $Id$
 */

class DateToMdyhiFormatFilter extends lmbMacroFunctionBasedFilter
{
  protected $function = 'date_to_mdyhi';
  protected $include_file = 'src/macro/filters.inc.php';
}
