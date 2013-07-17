<?php
//THIS FILE IS GENERATED AUTOMATICALLY, DON'T TOUCH IT! 

class EnumLoggerEvent
{
  const CLASS_ID = 91501285;

  const Login = 1;
  const levelUp = 2;
  const featureUse = 3;
  const costStock = 4;
  const addStock = 5;
  const NewPlayer = 6;
  const OutEnergy = 7;
  const payCost = 8;
  const QuestDone = 9;
  const QuestStart = 10;
  const QuestTaskComplete = 11;
  const viralRecive = 12;
  const viralSend = 14;
  const realPay = 15;
  const shopOpen = 16;

  const DEFAULT_VALUE = 1; // Login;


  static function isValueValid($value)
  {
    $values_list = self::getValuesList();
    return in_array($value, self::$values_list_);
  }

  static private $values_map_;

  static function getValueByName($name)
  {
    if(!self::$values_map_)
    {
      self::$values_map_ = array(
       'Login' => 1,'levelUp' => 2,'featureUse' => 3,'costStock' => 4,'addStock' => 5,'NewPlayer' => 6,'OutEnergy' => 7,'payCost' => 8,'QuestDone' => 9,'QuestStart' => 10,'QuestTaskComplete' => 11,'viralRecive' => 12,'viralSend' => 14,'realPay' => 15,'shopOpen' => 16
      );
    }
    if(!isset(self::$values_map_[$name]))
      ASSERT_TRUE(false, "Value with name $name isn't defined in enum EnumLoggerEvent. Accepted: " . implode(',', self::getNamesList()));
    return self::$values_map_[$name];
  }

  static function checkValidity($value)
  {// throws exception if $value is not valid numeric enum value
    if(!is_numeric($value))
      ASSERT_TRUE(false, "Numeric expected but got $value");
    if(!self::isValueValid($value))
      ASSERT_TRUE(false, "Numeric value $value isn't value from enum EnumLoggerEvent. Accepted numerics are " . implode(',', self::getValuesList()) . " but better to use one of names instead: " . implode(',', self::getNamesList()));
  }

  static private $values_list_;
  static function getValuesList()
  {
    if(!self::$values_list_)
    {
      self::$values_list_ = array(
          1,2,3,4,5,6,7,8,9,10,11,12,14,15,16
          );
    } 
    return self::$values_list_;
  }

  static private $names_list_;
  static function getNamesList()
  {
    if(!self::$names_list_)
    {
      self::$names_list_ = array(
          'Login','levelUp','featureUse','costStock','addStock','NewPlayer','OutEnergy','payCost','QuestDone','QuestStart','QuestTaskComplete','viralRecive','viralSend','realPay','shopOpen'
          );
    } 
    return self::$names_list_;
  } 
  
  static function getNameByValue($value)
  {
    if(!self::$values_map_)
    {
      self::$values_map_ = array(
       'Login' => 1,'levelUp' => 2,'featureUse' => 3,'costStock' => 4,'addStock' => 5,'NewPlayer' => 6,'OutEnergy' => 7,'payCost' => 8,'QuestDone' => 9,'QuestStart' => 10,'QuestTaskComplete' => 11,'viralRecive' => 12,'viralSend' => 14,'realPay' => 15,'shopOpen' => 16
      );
    }
    self::checkValidity($value);
    $flip = array_flip(self::$values_map_);
    
    if(!isset($flip[$value]))
      ASSERT_TRUE(false, "Value $value isn't defined in enum EnumLoggerEvent. Accepted: " . implode(',', self::getValuesList()));
    return $flip[$value];    
  }
}
 