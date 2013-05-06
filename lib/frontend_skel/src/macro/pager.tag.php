<?php

require_once('limb/macro/src/tags/pager/pager.tag.php');
/**
 * @tag pager
 * @package macro
 * @version $Id$
 */
class MacroPagerTag extends lmbMacroPagerTag
{
  protected $widget_include_file = 'src/macro/MacroPagerHelper.class.php';
  protected $widget_class_name = 'MacroPagerHelper';
}
