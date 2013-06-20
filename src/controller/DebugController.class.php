<?php

class DebugController extends spController {
  
  function doDisplay() {
    $nginx = "";
    $fp = fopen('/var/log/stats2/nginx-data.error.log',"r");
    if(!$fp)
      $nginx = "Error\r\n";
    while (!feof($fp))
      $nginx .= fgets ($fp);
    fclose($fp);

    $combine = "";
    $fp = fopen('/home/stats2/stats2/admin/var/log/combine.log',"r");
    while (!feof($fp))
      $combine .= fgets ($fp);
    fclose($fp);

   $db = $this->toolkit->getDefaultDbConnection();

   $tables_db = $db->execute("SHOW TABLES LIKE 'log_%'");
   $tables = array();

   while($row=$tables_db->fetch_row())
    $tables[] = $row[0];
    $tables=array('log_QuestTaskComplete');
   $result = array();

   foreach($tables as $table_name){
    if($table_name=="log_MAU")
      continue;
    $table_db = $db->execute("SELECT * FROM $table_name WHERE project_id=1 ORDER BY id DESC limit 1,10;");
    $tmp = array();
    while($row = $table_db->fetch_assoc())
      $tmp[] = $row;
    $result[$table_name] = $tmp;
   }

   $text = "";
   foreach($result as $key=>$value) {
    $text .= "<table><tr><th>$key</th></tr>";
    if(!isset($value[0]))
      continue;
    $keys = array_keys($value[0]);
    $text .= "<tr><th>".(implode("</th><th>",$keys))."</th></tr>";
    foreach($value as $row) {
      $text .= "<tr><td>";
      foreach($keys as $key)
        $text .= trim($row[$key])."</td><td>";
      $text .="&nbsp</td></tr>";
    }
    $text .= "</table>";
   }

    $this->view->set('nginx_error',$nginx);
    $this->view->set('combine_error',$combine);    
    $this->view->set('tables_rows',$text);    
  }
  
}
