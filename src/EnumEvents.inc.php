<?php

$events_list = array(
  1 =>'login',
  2 =>'levelUp',
  3 =>'featureUse',
  4 =>'spendResource',
  5 =>'getResource',
  6 =>'register',
  7 =>'OutOfEnergy',
  8 =>'buyResource',
  9 =>'questDone',
  10=>'questStart',
  11=>'questTaskComplete',
  12=>'getResourceViral',
  14=>'sendResourceViral',
  15=>'buyCurrency',
  16=>'shopOpen',
  17=>'openWindow',
);

function event_get_name($id) {
  global $events_list;
  if(!isset($events_list[$id]))
    throw new Exception("Undefined event id: $id");
  return $events_list[$id];
}


 