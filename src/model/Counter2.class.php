<?php
/** 
 * @author Sync
 */

class Counter2 extends lmbActiveRecord {
  protected $_db_table_name = 'counter2'; 
  
  function __construct($id = null,$magic_params = null, $conn = null) {
    parent::__construct($magic_params, $conn);
    if(!$id)
      return;
    $this->loadById($id);
  }
  
}
