<?php

class AccessFilter implements lmbInterceptingFilter
{
  function run($filter_chain)
  {
    $toolkit = lmbToolkit :: instance();
    $controller = $toolkit->getDispatchedController();
    
    if( $controller->getName() !== 'login' && 
        $controller->getName() !== 'not_found' &&       
        $controller->getName() !== 'api' &&
        $controller->getName() !== 'stats_logger' &&
        $controller->getName() !== 'from_client' &&            
        $controller->getName() !== 'statslogger' &&
        !($toolkit->getMember()->isLoggedIn()) ) {
      
      $toolkit->redirect('login');      
      return;
    }    
    
    $filter_chain->next();
  }
}
