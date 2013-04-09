<?php
/**
 * @author Sync
 */
class Pager extends lmbActiveRecord{
    //`id`  int(3) unsigned NOT NULL auto_increment,
    //`project_id`  int(3) UNSIGNED NOT NULL DEFAULT '0',
    //`member_id`    int(3) unsigned NOT NULL DEFAULT '0',    
    //`name`   VARCHAR(500) NOT NULL DEFAULT 'Имя',
    private $_views = array();
    
    public function addPage($name,$project,$member) {
      $this->set('project_id',intval($project));
      $this->set('member_id',intval($member));
      $this->set('name',strval($name));
      $this->save();
    }
    
    public function loadViewByPage($pager_id) {      
      $views = self::find('page_view',array('criteria'=>'page_id='.intval($pager_id)));
      $this->_views = array();
      foreach ($views as $view_record) {
        $item = new PageView();
        $item->loadFromRecord($view_record);
        $this->_views[] = $item;
      }
    }
    
    public function getViews() {
      return $this->_views;
    }
            
}

