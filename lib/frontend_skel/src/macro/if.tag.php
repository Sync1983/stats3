<?php

lmb_require('limb/macro/src/compiler/lmbMacroTag.class.php');
/**
 * class IfMacroTag
 * @tag if
 * @req_attributes for
 */
class IfMacroTag extends lmbMacroTag
{
  protected function _generateContent($code)
  {
    if($this->get('to'))
    {
      $var = $this->get('to');
      $code->writePHP(''.$var.'='.$this->get('for').';');
    }
    else
      $var = $this->get('for');
    $code->writePHP('if('.$var.') {');
    $isset_else = false;
    foreach($this->children as $child)
    {
      if($child instanceof ElseMacroTag)
      {
        if($isset_else)
          return $this->raise('Tag {{else}} already exists!');
        $isset_else = true;
        $code->writePHP('} else { ');
        $child->generate($code);
      }
      else
        $child->generate($code);
    }
    $code->writePHP('};');
  }
}
