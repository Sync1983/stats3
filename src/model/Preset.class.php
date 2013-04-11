<?php
/** 
 * @author user
 */
class Preset extends lmbActiveRecord {
  protected $_db_table_name = 'preset'; 
  
  function __construct($id = null, $magic_params = null, $conn = null) {
    parent::__construct($magic_params, $conn);
    if(!$id)
      return;
    $this->loadById($id);
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
