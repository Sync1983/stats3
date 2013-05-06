<?php

/**
 * class PeriodMacroFilter
 *
 * @filter period
 * @version $Id$
 */

class PeriodMacroFilter extends lmbMacroFunctionBasedFilter
{
  protected $function = 'time_period_macro';
  protected $include_file = 'src/macro/filters.inc.php';
}
