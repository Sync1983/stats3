<?php

lmb_require('limb/macro/src/compiler/lmbMacroTag.class.php');
/**
 * class AClickTag
 * @tag __a
 * @forbid_end_tag
 */
class AClickTag extends lmbMacroTag
{
  protected function _generateContent($code)
  {
    $code->writeHtml('onclick="return myjs.a(this, event)"');
  }
}
