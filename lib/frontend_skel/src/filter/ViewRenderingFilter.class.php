<?php

lmb_require_class('limb/web_app/src/filter/lmbViewRenderingFilter.class.php', 'lmbViewRenderingFilter');

class ViewRenderingFilter extends lmbViewRenderingFilter
{
  function run($filter_chain)
  {
    if(lmbToolkit :: instance()->skipViewRender())
      return $filter_chain->next();
    parent :: run($filter_chain);
  }
}
