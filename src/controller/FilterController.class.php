<?php

class FilterController extends spController {

  function doAjaxLoadConstructor() {
    $this->view = $this->toolkit->createViewByTemplate('filter/ajax_load_constructor.phtml');    
    $this->sendAjaxResponce(array(),true);
  }
}
