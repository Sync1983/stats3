<?php

class Purse
{
  protected $member_id;
  protected $type;

  function __sleep()
  {
    return array('member_id', 'type');
  }

  function __construct($member_id, $type)
  {
    $this->member_id = $member_id;
    if(!is_int($type) || $type <= 0 || $type > 255)
      throw new Exception('Bad purse type "'.$type.'"');
    $this->type = $type;
  }

  private function _cacheKey()
  {
    return 'pse-'.base_convert($this->member_id, 10, 32).'-'.$this->type;
  }

  function tryPay($amount)
  {
    if(!is_int($amount) || $amount < 0)  
      throw new Exception('Bad amount value - '.$amount);
    if(!$amount)
      return true;
    bit_memcache_set($this->_cacheKey(), null);
    return 1 == dbal()->fetchOneValue('SELECT try_pay('.intval($this->member_id).', '.intval($amount).', '.intval($this->type).')');
  }

  function getAmount()
  {
    $value = (int) dbal()->fetchOneValue('SELECT amount FROM purse WHERE member_id='.intval($this->member_id).' AND type='.intval($this->type));
    bit_memcache_set($this->_cacheKey(), $value);
    return $value;
  }

  function getCacheAmount()
  {
    if(null !== ($value = bit_memcache_get($this->_cacheKey())))
      return $value;
    return $this->getAmount();
  }

  function add($amount)
  {
    if($amount < 0)  
      throw new Exception('Bad amount value - '.$amount);
    dbal()->execute(
      'INSERT INTO purse (member_id, amount, type) '.
      ' VALUES('.intval($this->member_id).','.intval($amount).', '.intval($this->type).') '.
      'ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount)');
    bit_memcache_set($this->_cacheKey(), null);
  }
}

/*
SQL:

DROP FUNCTION  IF EXISTS try_pay;
delimiter //;
CREATE FUNCTION try_pay (var_member_id INT unsigned, var_amount INT unsigned, var_type TINYINT unsigned) RETURNS TINYINT(1)
NOT DETERMINISTIC 
NO SQL
BEGIN
declare var_balance INT unsigned default 0;
SELECT amount into var_balance FROM purse WHERE `purse`.`member_id` = var_member_id AND `purse`.`type` = var_type;
if(var_balance IS NULL) THEN
  RETURN 0;
ELSEIF (var_balance < var_amount) THEN
  RETURN 0;
END IF; 
UPDATE purse SET amount = if(amount > var_amount, amount - var_amount, 0) WHERE member_id=var_member_id AND type=var_type; 
RETURN 1;
END;
//;
delimiter ;

*/
