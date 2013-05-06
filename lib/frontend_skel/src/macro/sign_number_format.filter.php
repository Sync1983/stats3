<?php

/**
 * class NumberFormatFilter
 *
 * @filter snumber_format
 * @version $Id$
 */

class SignNumberFormatFilter extends lmbMacroFunctionBasedFilter
{
  protected $function = 'sign_number_format';
  protected $include_file = 'src/macro/filters.inc.php';
}
