<?php

class Debenture
{
  protected $_purse;
  protected $_debit = 0;

  function __sleep()
  {
    return array('_purse', '_debit');
  }

  function __construct($member_id, $type)
  {
    $this->_purse = new Purse($member_id, $type);
    $this->_debit = 0;
  }
  
  function tryPay($amount)
  {
    if(!is_int($amount) || $amount < 0)  
      throw new Exception('Bad amount value - '.$amount);
    if($this->_debit < 0 && abs($this->_debit) > $amount)
    {
      $this->_debit += $amount;
      return true;
    }
    if(($this->_debit + $amount) > $this->_purse->getAmount())
      return false;
    $this->_debit += $amount;
    return true;
  }

  function tryRepayDebts()
  {
    if($this->_debit > 0)
    {
      if($this->_purse->tryPay($this->_debit))
      {
        $this->_debit = 0;
        return true;
      }
      return false;
    }
    else
    {
      $this->_purse->add(abs($this->_debit));
      $this->_debit = 0;
      return true;
    }
  }

  function getAmount()
  {
    return max(0, $this->_purse->getAmount() - $this->_debit);
  }

  function getPurseAmount()
  {
    return $this->_purse->getAmount();
  }

  function add($amount)
  {
    if(!is_int($amount) || $amount < 0)  
      throw new Exception('Bad amount value - '.$amount);
    $this->_debit -= $amount;
  }
}
