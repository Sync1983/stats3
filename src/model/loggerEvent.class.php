<?php

class loggerEvent extends lmbActiveRecord
{
  protected $_db_table_name = '';
  
  public function addItem($project_id,$data)
  {
    if(!is_object($data))
      throw new Exception('logger data is not object!');    
    $this->set('project_id', $project_id);    
    $this->set('ext_id',  $data->id);
    $this->set('stamp',   $data->time_stamp);
    //$this->set('event',   $data->event);
    $this->set('item_id', $data->item_id);
    $this->set('value',   $data->value);
    try{
      $this->set('level',   $data->lvl); 
      $this->set('session', $data->session);
      $this->set('return',  $data->return_day);
      $this->set('energy',  $data->energy);
      $this->set('real',    $data->real);
      $this->set('bonus',   $data->bonus);
      $this->set('money',   $data->moneys);
      if(isset($data->referal))
        $this->set('referal',   $data->referal);
      if(isset($data->reg_time))
        $this->set('reg_time',   $data->reg_time);
    }catch(Exception $E)
    {
      echo "Struct error: ".$E->getMessage()."\r\n";
    };
    
    $json_data = $data->data;
    if($json_data)
      foreach ($json_data as $key=>$value)
        $this->set($key, $value);
    //$this->set('data', json_encode($data->data));
    $this->save(); 
  }
  
}
?>
