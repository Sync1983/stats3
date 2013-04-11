<?php
/**
 * @author Sync
 */
class PageView extends lmbActiveRecord {
    //page_id    
    //position
    //data_type
    //counter_id
    //view_preset
    private $_views;
    
    public function __construct($page_id = null, $magic_params = null, $conn = null) {
      parent::__construct($magic_params, $conn);
      if(!$page_id)
        return;
      $this->_views = array();
      $view_records = lmbActiveRecord::find('PageView', array('criteria'=>"page_id=$page_id"));
      foreach ($view_records as $record) {
        $view = new PageView();
        $view->loadFromRecord($record);
        $this->_views[] = $view;
      }
      function orderSort($a, $b) { 
        if ($a->get('position')<$b->get('position'))
          return 1;
        else if ($a->get('position')>$b->get('position'))
          return -1;
        else 
          return 0;
      } 
      uasort($this->_views, 'orderSort');
      return;
    }
    
    public function getPageViews(){
      return $this->_views;
    }


}