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
      
      $db = lmbActiveRecord::getDefaultConnection();      
      $result = $db->execute("SELECT preset.*,page_view.* FROM preset,page_view WHERE page_view.counter_id=preset.id and page_view.page_id=$page_id and page_view.data_type=0;");
      while($record = $result->fetch_assoc()) {         
        $counter_id   = $record['counter_id'];        
        $view_preset  = $record['view_preset'];
        $data_type    = $record['data_type'];
        $this->_views[$record['position']*1] = 
                          array(  'id'          =>  ($record['page_id']<<16)+$record['position'],
                                  'counter_id'  => $counter_id,
                                  'view_preset' => $view_preset,
                                  'data_type'   => $data_type,
                                  'name'        => $record['name']);
      };        
      $result->free();  
      
      $result = $db->execute("SELECT logger_chart.*,page_view.* FROM logger_chart,page_view WHERE page_view.counter_id=logger_chart.id and page_view.page_id=$page_id and page_view.data_type=1;");
      while($record = $result->fetch_assoc()) {         
        $counter_id   = $record['counter_id'];        
        $view_preset  = $record['view_preset'];
        $data_type    = $record['data_type'];
        $this->_views[$record['position']*1] = 
                          array(  'id'          =>  ($record['page_id']<<16)+$record['position'],
                                  'counter_id'  => $counter_id,
                                  'view_preset' => $view_preset,
                                  'data_type'   => $data_type,
                                  'name'        => $record['name']);
      };        
      $result->free(); 
      ksort($this->_views,SORT_NUMERIC);      
      return;
    }
    
    public function getPageViews(){
      return $this->_views;
    }
    
    public function deleteChart($page_id,$position) {
      $db = lmbActiveRecord::getDefaultConnection();      
      if($db->execute("DELETE FROM page_view WHERE page_id=$page_id and position=$position"))
        return true;
      return false;
    }
    
    public function  deleteAllCharts($page_id) {
      $db = lmbActiveRecord::getDefaultConnection();      
      if($db->execute("DELETE FROM page_view WHERE page_id=$page_id"))
        return true;
      return false;
    }
    
    public function changeChartType($page_id,$position,$type) {
      $db = lmbActiveRecord::getDefaultConnection();      
      if($db->execute("UPDATE page_view SET view_preset=$type WHERE page_id=$page_id and position=$position"))
        return true;
      return false;
    }
    
    


}