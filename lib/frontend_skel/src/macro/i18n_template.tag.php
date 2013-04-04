<?php

//{{i18nt message="Перейдите по|ссылке"}}{$in_1}<a href="$url">{$in_2}</a>{{/i18nw}}

lmb_require('limb/macro/src/compiler/lmbMacroNode.class.php');
lmb_require('limb/macro/src/compiler/lmbMacroTag.class.php');

/**
 * class I18nTemplateTag.
 * @tag i18nt
 */
class I18nTemplateTag extends lmbMacroTag
{
  protected function _generateContent($code)
  {
    if(!$this->has('context'))
    {
      if($this->has('c'))
        $this->set('context', $this->get('c'));
      else
        $this->set('context', 'macro');
    }
    
    if(!$this->has('message'))
    {
      if($this->has('m'))
        $this->set('message', $this->get('m'));
      else
        $this->raise('Message required attribute!');
    }

    if($this->isDynamic('message'))
      $this->raise('Not support dynamic message!');

    $message = lmb_i18n($this->get('message'), $this->get('context'));

    $items = self :: parseMessage($message);
    if(count(self :: parseMessage($this->get('message'))) !== count($items))
      $this->raise('Wrong translation template!');

    foreach($items as $name => $value)
      $code->writePHP('$in_'.$name.'=\''.str_replace(array('\\', '\''), array('\\\\', '\\\''), $value).'\';');
    parent :: _generateContent($code);
  }
  
  static function parseMessage($message)
  {
    $out = explode('|', $message);
    $vars = array();
    $i=0;
    foreach($out as $item)
      $vars[++$i] = $item;
    return $vars;
    /*if(!preg_match_all('/\{#([a-z0-9\_]*):([^\}]*)\}/i', $message, $out, PREG_SET_ORDER))
      throw new Exception('Bad format message string');
    $vars = array();
    foreach($out as $item)
      $vars[$item[1]] = $item[2];
    return $vars;*/
  }
}
