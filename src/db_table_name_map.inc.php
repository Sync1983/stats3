<?php

$db_table_name_map = array(
    'addStock'    => 'log_addStock',
    'costStock'   => 'log_costStock',
    'featureUse'  => 'log_FeatureUse',
    'levelUp'     => 'log_LevelUp',
    'Login'       => 'log_Login',
    'NewPlayer'   => 'log_NewPlayer',
    'OutEnergy'   => 'log_OutEnergy',
    'payCost'     => 'log_payCost',
    'QuestDone'   => 'log_QuestDone',
    'QuestStart'  => 'log_QuestStart',
    'QuestTaskComplete' => 'log_QuestTaskComplete',
    'viralRecive' => 'log_viralRecive',
    'viralSend'   => 'log_viralSend',
    'realPay'     => 'log_Pay',
    'shopOpen'    => 'log_shopOpen',
);

$db_table_std_fields = array(
    'project_id',
    'ext_id',
    'stamp',
    'item_id',
    'value',
    'level',
    'session',
    'return',
    'energy',
    'real',
    'bonus',
    'money',
    'referal',
    'reg_time',    
);

$db_table_adt_fields = array(
    'addStock'    => array('data'),
    'costStock'   => array('data'),
    'featureUse'  => array('pack','name'),
    'levelUp'     => array(),
    'Login'       => array('sex','age','fb_source','country'),
    'NewPlayer'   => array('sex','age','fb_source','country'),
    'OutEnergy'   => array(),
    'payCost'     => array('pack','name','currencyName'),
    'QuestDone'   => array(),
    'QuestStart'  => array(),
    'QuestTaskComplete' => array('completeTask'),
    'viralRecive' => array('type','name'),
    'viralSend'   => array('type','name'),
    'realPay'     => array('sex','age','fb_source','country'),
    'shopOpen'    => array('sex','age'),
);

function stat_get_db_fields($event_name) {
  GLOBAL $db_table_adt_fields;
  GLOBAL $db_table_std_fields;
  GLOBAL $db_table_name_map;
  $result = $db_table_std_fields;
  if(!isset($db_table_name_map[$event_name])) {
    echo "Undefined event name: $event_name\r\n";
    return null;
  }
  elseif(isset($db_table_adt_fields[$event_name]))
    $result = array_merge ($db_table_adt_fields[$event_name]);    
  return $result;
}

function stat_normalize_event_data($event_name,$event,$project_id) {
  $fields = stat_get_db_fields($event_name);
  if(!$fields)
    return null;
  $result = array();
  $data = isset($event['data'])?$event['data']:array();
  
  foreach ($fields as $field_name) {
    if($field_name=="project_id") {
      $result[] = $project_id;
      continue;
    }
    if(isset($data[$field_name]))
      $result[] = "'".$data[$field_name]."'";
    elseif(isset($event[$field_name]))
      $result[] = "'".$event[$field_name]."'";
    else
      $result[] = "''";
  }
  return "(".implode(",", $result).")";
}