<?php

lmb_require('limb/macro/src/compiler/lmbMacroNode.class.php');
lmb_require('limb/macro/src/compiler/lmbMacroTag.class.php');

/**
 * class ICacheMacroTag
 * @tag icache
 * @req_attributes name
 * @restrict_self_nesting
 */
class ICacheMacroTag extends lmbMacroTag
{
  protected function _generateContent($code)
  {
    $value = $code->generateVar();
    $key = $code->generateVar();
    $name_cache = $this->get('name');
    $name_cache = $this->isDynamic('name') ? $name_cache : "'$name_cache'";

    $vars = $this->get('vars');
    if(is_null($vars))
      $vars = 'array()';
    else
      $vars = "$vars";

    $code->writePHP("list({$value}, {$key}) = \$this->toolkit->getCacheService()->macroCacheGet({$name_cache}, {$vars});\n");
    $code->writePHP("if(!is_null({$value})) { \n");
    $code->writePHP("  echo {$value};\n");
    $code->writePHP("} else { \n");
    $code->writePHP("  ob_start();\n");
    parent :: _generateContent($code);
    $code->writePHP("  {$value} = ob_get_contents();\n");
    $code->writePHP("  ob_end_flush();\n");
    $code->writePHP("  \$this->toolkit->getCacheService()->macroCacheSet({$key}, {$value});\n");
    $code->writePHP("};\n");
    $code->writePHP("unset({$value}, {$key});\n");
  }
}
