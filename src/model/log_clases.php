<?php

class log_addStock extends loggerEvent
{
  protected $_db_table_name = 'log_addStock';
};

class log_costStock extends loggerEvent
{
  protected $_db_table_name = 'log_costStock';
}

class log_FeatureUse extends loggerEvent
{
  protected $_db_table_name = 'log_featureUse';
}

class log_LevelUp extends loggerEvent
{
  protected $_db_table_name = 'log_levelUp';
}

class log_Login extends loggerEvent
{
  protected $_db_table_name = 'log_Login';
}

class log_NewPlayer extends loggerEvent
{
  protected $_db_table_name = 'log_NewPlayer';
}

class log_OutEnergy extends loggerEvent
{
  protected $_db_table_name = 'log_OutEnergy';
}

class log_payCost extends loggerEvent
{
  protected $_db_table_name = 'log_payCost';
}

class log_QuestDone extends loggerEvent
{
  protected $_db_table_name = 'log_QuestDone';
}

class log_QuestStart extends loggerEvent
{
  protected $_db_table_name = 'log_QuestStart';
}

class log_QuestTaskComplete extends loggerEvent
{
  protected $_db_table_name = 'log_QuestTaskComplete';
}

class log_viralRecive extends loggerEvent
{
  protected $_db_table_name = 'log_viralRecive';
}

class log_viralSend extends loggerEvent
{
  protected $_db_table_name = 'log_viralSend';
}

?>
