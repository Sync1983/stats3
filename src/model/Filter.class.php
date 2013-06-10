<?php
/**
 * @author Sync
 */
class Filter extends lmbActiveRecord{
    protected $_db_table_name = 'filter';    
    
    public function getFiltersForProject($project_id) {      
      $filters = self::find('filter',array('criteria'=>'project_id='.intval($project_id)));      
      return $filters;
    }
            
}

