<?php
/** 
 * @author user
 */
class LoggerChart extends lmbActiveRecord {
  protected $_db_table_name = 'logger_chart'; 
  
  public function getTable() {
    return $this->_db_table_name;
  }
  
}
