<?php

/**
 * class AjaxSlotMacroTag.
 *
 * @tag aslot
 * @forbid_end_tag    
 * @package macro
 * @version $Id$
 */
class AjaxSlotMacroTag extends lmbMacroTag
{
  protected function _generateContent($code)
  {
    $slot = $this->getNodeId();
    if(!$this->getBool('inline'))
    {
      $method = $code->beginMethod('__aslotHandler_'.$slot);
      parent :: _generateContent($code);
      $code->endMethod();
      $code->writePHP('$this->' . $method . '();');
    }
    else
      parent :: _generateContent($code);
  }
}
