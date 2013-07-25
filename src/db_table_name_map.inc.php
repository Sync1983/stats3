<?php

$db_table_name_map = array(
  1  => 'log_Login',
  2  => 'log_levelUp',
  3  => 'log_featureUse',
  4  => 'log_costStock',
  5  => 'log_addStock',
  6  => 'log_NewPlayer',
  7  => 'log_OutEnergy',
  8  => 'log_payCost',
  9  => 'log_QuestDone',
  10 => 'log_QuestStart',
  11 => 'log_QuestTaskComplete',
  12 => 'log_viralRecive',
  14 => 'log_viralSend',
  15 => 'log_Pay',
  16 => 'log_shopOpen',    
);

// project_id is 0
$db_table_std_fields = array(  
  'ext_id'  => 1,
  'time_stamp'   => 2,
  'item_id' => 3,
  'value'   => 4,
  'lvl'     => 5,
  'session' => 6,
  'return_day'  => 7,
  'energy'  => 8,
  'real'    => 9,
  'bonus'   => 10,
  'moneys'   => 11,
  'referal' => 12,
  'reg_time'=> 13,
  
  'id'      => 1,
  't'       => 2,
  'i'       => 3,
  'v'       => 4,
  'l'       => 5,
  's'       => 6,
  'rd'      => 7,
  'e'       => 8,
  'r'       => 9,
  'b'       => 10,
  'm'       => 11,
  'rf'      => 12,
  'rt'      => 13,
  
  'sex'     => 14,
  'age'     => 15,
  'fb_source'=>16,
  'country' => 17,  
    
  'pack'    => 14,
  'name'    => 15,
  'currencyName'>16,
    
  'completeTask' =>14,
  'type' => 14,
    
);

$db_table_adt_fields = array(    
    1  => '`sex`,`age`,`fb_source`,`country`',
    6  => '`sex`,`age`,`fb_source`,`country`',
    8  => '`pack`,`name`,`currencyName`',
    11 => '`completeTask`',
    //15 => '`sex`,`age`,`fb_source`,`country`',
    //16 => '`sex`,`age`,`fb_source`,`country`',
    //17 => '`sex`,`age`,`fb_source`,`country`',
);

/**
 * 
 * @global array $db_table_adt_fields
 * @global array $db_table_std_fields
 * @global array $db_table_name_map
 * @param int $event_id
 * @return null
 */

function stat_get_db_fields($event_id) {
  GLOBAL $db_table_adt_fields;
  $std = '`project_id`,`ext_id`,`stamp`,`item_id`,`value`,`level`,`session`,`return`,`energy`,`real`,`bonus`,`money`,`referal`,`reg_time`';
  if(isset($db_table_adt_fields[$event_id]))
    return $std.",".$db_table_adt_fields[$event_id];
  return $std;
}

/**
 * 
 * @param int $event_id
 * @param string $event
 * @param int $project_id
 * @return null
 */

function stat_normalize_event_data($event,$project_id,$event_id) {
  global $db_table_std_fields;
  
  $fields = $db_table_std_fields;
  $data = null;
  $data = isset($event['data'])?$event['data']:$data;
  $data = isset($event['d'])?$event['d']:$data;
  unset($event['data']);
  unset($event['d']);
  
  if($event['referal']=="")
    $event['referal'] = "none";
  elseif(($event['rf'] == "")&&(isset($event['rf'])))
    $event['rf']="none";
  
  $sql_fields = stat_get_db_fields($event_id);
  $max_len = substr_count($sql_fields,",");

  $result = array_fill(0,$max_len,0);
  $result[0] = $project_id;  
  foreach ($event as $key=>$value) {
    if(!$fields[$key]) {
      echo "Undefined field index: $key in (".json_encode($event).") event: $event_id\r\n";
      continue;
    }
    $index = $fields[$key];
    if($index>$max_len)
      continue;
    $result[$index] = $value;
  } 
  
  if($data)
    foreach ($data as $key=>$value) {
    if(!$fields[$key]) {
      echo "Undefined field index: $key in (".json_encode($event).") event: $event_id \r\n";
      continue;
    }
    $index = $fields[$key];
    if($index>$max_len)
      continue;
    $result[$index] = $value;
  } 

  ksort($result); 
  return "('".implode("','", $result)."')";
}
