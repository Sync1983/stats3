<?php

require_once(dirname(__FILE__) . '/../setup.php');

$dsn = lmbToolkit :: instance()->getDefaultDbDSN();

$host = $dsn->getHost();
$user = $dsn->getUser();
$password = $dsn->getPassword();
$database = $dsn->getDatabase();

$pos = 0;
$limit = 100000;
$tab_name = 'log_QuestTaskComplete';
$db = mysqli_connect($host, $user, $password, $database);
while(true)
{
  $sql =  "SELECT id,data FROM $tab_name LIMIT $pos,$limit FOR UPDATE;";
  if(!$result = $db->query($sql))
    break;
  if($result->num_rows==0)
    break;
  $pos += $result->num_rows;
  while($row = $result->fetch_assoc())
  {
    $id = $row['id'];
    $data = json_decode($row['data']);
    if(!$data)
      continue;
    $tmp = array();
/*    if(isset($data->sex))
    {
      if($data->sex=="MALE")
       $data->sex = 1;
      elseif($data->sex=="FEMALE")
       $data->sex = 2;
      else
        $data->sex = 0;
    }
    foreach ($data as $key=>$value)
      if(($value)&&($value!=''))
        $tmp[] = "$key = '$value'";    
*/
    $tmp[]="completeTask = ".$data->completeTask;
    $insert = "UPDATE $tab_name SET ".(implode(',', $tmp))." WHERE id=$id";
    $db->query($insert);    
    //echo "$insert \r\n";
  }
  echo "Updated $pos fields\r\n";
}
$db->close();
?>
