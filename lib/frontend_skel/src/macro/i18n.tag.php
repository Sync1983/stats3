<?php
lmb_require('limb/macro/src/compiler/lmbMacroNode.class.php');
lmb_require('limb/macro/src/compiler/lmbMacroTag.class.php');

/**
 * class I18nTag.
 * @aliases i
 * @tag i18n
 */
class I18nTag extends lmbMacroTag
{
  protected $_context;
  protected $_message;
  protected $_is_dynamic = false;
  protected static $_not_vars = array('message', 'context', 'c', 'm');

  protected function _isDynamic()
  {
    foreach($this->attributes as $k => $attribute)
    {
      if(in_array($attribute->getName(), self :: $_not_vars))
        continue;
      if($this->isDynamic($attribute->getName()))
        return true;
    }
    return false;
  }

  protected function _setContext()
  {
    if(!$this->has('context'))
    {
      if($this->has('c'))
        $this->set('context', $this->get('c'));
      else
        $this->set('context', 'macro');
    }
  }

  protected function _generateContent($code)
  {
    $this->_setContext();
    
    if(!$this->has('message'))
    {
      if($this->has('m'))
        $this->set('message', $this->get('m'));
      else
        $this->raise('Message required attribute!');
    }

    $this->_is_dynamic = $this->_isDynamic();

    if($this->_is_dynamic || $this->isDynamic('message'))
      $this->_generateDinamicaly($code);
    else
      $this->_generateStaticaly($code);
  }
  
  protected function _generateDinamicaly($code)
  {                               
    if(!$this->isDynamic('message'))
    {
      $message = lmb_i18n($this->get('message'), $this->get('context'));
      list($key_str, $arg_str) = $this->attributesIntoArrayEscapeForReplace();
      $code->writePHP('echo str_replace('.$key_str.', '.$arg_str.', \''.str_replace(array('\\', '\''), array('\\\\', '\\\''), $message).'\');');
    } 
    else 
    {
      $code->writePHP('echo lmb_i18n(');
      $code->writePHP($this->getEscaped('message').',');
      $code->writePHP($this->attributesIntoArrayEscapeString().',');
      $code->writePHP($this->getEscaped('context'));
      $code->writePHP(');');   
    }
  }

  protected function _generateStaticaly($code)
  {
    $code->writeHTML(lmb_i18n($this->get('message'), $this->attributesIntoArrayEscapeOfVar(), $this->get('context')));  
  }
  
  protected function _getEscapedPhpContent($attribute)
  {
    if($attribute->isDynamic())
      return "htmlspecialchars(".$this->getEscaped($attribute->getName()).", ENT_QUOTES)";
    else
      return '\''.htmlspecialchars($this->get($attribute->getName()), ENT_QUOTES).'\'';
  }

  function attributesIntoArrayEscapeString()
  {
    $arg_str = 'array(';
    foreach($this->attributes as $k => $attribute)
    {
      if(in_array($attribute->getName(), self :: $_not_vars))
        continue;
      $arg_str .= "'$".$attribute->getName()."' => ".$this->_getEscapedPhpContent($attribute).",";
    };
    $arg_str .= ')';
    
    return $arg_str;
  }

  function attributesIntoArrayEscapeForReplace()
  {
    $arg_str = 'array(';
    $key_str = 'array('; 
    foreach($this->attributes as $k => $attribute)
    {
      if(in_array($attribute->getName(), self :: $_not_vars))
        continue;
      $arg_str .= $this->_getEscapedPhpContent($attribute) . ',';
      $key_str .= "'$".$attribute->getName()."',";
    }
    $arg_str .= ')';
    $key_str .= ')';
    
    return array($key_str, $arg_str);
  }

  function attributesIntoArrayEscapeOfVar()
  {
    $arr = array();
    foreach($this->attributes as $k => $attribute)
    {
      $name = $attribute->getName();
      if(in_array($name, self :: $_not_vars))
        continue;
      
      $arr['$'.$name] = htmlspecialchars($this->get($k), ENT_QUOTES);
    }     
    return $arr;
  }
}
