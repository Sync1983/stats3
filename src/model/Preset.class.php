<?php
/** 
 * @author user
 */
class Preset extends lmbActiveRecord {
  protected $_db_table_name = 'preset'; 
  
  function __construct($id = null) {
    parent::__construct();
    if(!$id)
      return;
    $this->loadById($id);
  }
  
  public function getTable() {
    return $this->_db_table_name;
  }

  public function getData() {
    return $this->get('data');
  }
  
  public function getProjectId() {
    return $this->get('project_id');
  }
  
  public function getName() {
    return $this->get('name');
  }
  
  public function getValuesName() {
    return $this->get('v_name');
  }
  
}
