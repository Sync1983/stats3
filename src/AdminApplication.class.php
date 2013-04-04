<?php

lmb_require_class('limb/web_app/src/lmbWebApplication.class.php');
lmb_require_class('limb/filter_chain/src/lmbInterceptingFilter.interface.php');
lmb_require_class('limb/web_app/src/request/lmbRoutesRequestDispatcher.class.php');

class AdminApplication extends lmbWebApplication
{
  protected function _registerFilters()
  {
    $this->registerFilter(new lmbHandle('limb/web_app/src/filter/lmbRequestDispatchingFilter',
                                        array(new lmbRoutesRequestDispatcher(),
                                              'not_found')));
    $this->registerFilter(new lmbHandle('limb/web_app/src/filter/lmbResponseTransactionFilter'));
    $this->registerFilter(new lmbHandle('src/AccessFilter'));
    $this->registerFilter(new lmbHandle('limb/web_app/src/filter/lmbActionPerformingFilter'));    
  }
}

