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
    private $_views = array();
    
    public function __construct($page_id = null) {
      parent::__construct();
      if(!$page_id)
        return;      
      $view_records = $this->findAllRecords("page_id=".$page_id,array('position'=>'asc'));
      foreach ($view_records as $record) {        
        $counter_id = $record['counter_id'];        
        $this->_views[] = array(  'id'    =>  ($record['page_id']<<16)+$record['position'],
                                  'counter_id' => $counter_id);
      }
      foreach ($this->_views as $key=>$view) {
        $preset = lmbActiveRecord::findOneBySql('Preset', "SELECT name FROM preset WHERE id=".$view['counter_id']);
        $view['name'] = $preset->_getRaw('name');
        $this->_views[$key] = $view;
      }      
      return;
    }
    
    public function getPageViews(){
      return $this->_views;
    }
    
    public function deleteChart($page_id,$position) {
      $db = lmbActiveRecord::getDefaultConnection();
      echo "DELETE FROM page_view WHERE page_id=$page_id and position=$position";
      if($db->execute("DELETE FROM page_view WHERE page_id=$page_id and position=$position"))
        return true;
      return false;
    }


}