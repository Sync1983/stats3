<?php

/**
 * class AjaxWrapMacroTag.
 *
 * @tag awrap
 * @req_attributes file
 * @package macro
 */
class AjaxWrapMacroTag extends lmbMacroTag
{
  protected static $static_includes_counter = 0;

  function preParse($compiler)
  {
    parent :: preParse($compiler);
    $file = $this->get('file');
    $compiler->parseTemplate($file, $this);

  }

  protected function _generateContent($code)
  {
    $method = $code->beginMethod('getContentForAjaxSlot', array("\$slot"));
    $code->writePHP("\$method = '__aslotHandler_'.\$slot; if(method_exists(\$this, \$method)) { ob_start(); \$this->\$method(); return ob_get_clean(); }");
    $code->endMethod();

    list($keys, $vals) = $this->attributesIntoArgs();
    $method = $code->beginMethod('__astaticInclude' . (++self :: $static_includes_counter), $keys);
    parent :: _generateContent($code);
    $code->endMethod();
    $code->writePHP(
      'if(isset($this->render_ajax_proxy) && is_object($this->render_ajax_proxy)) {'.
        '$slots=array();'.
        'foreach($this->render_ajax_proxy->get("slots") as $slot) {'.
          '$slots[$slot] = $this->getContentForAjaxSlot($slot);'.
        '}'.
        '$this->render_ajax_proxy->set("response", $slots);'.
        'return;'.
      '}'
    );
    $code->writePHP('$this->' . $method . '(' . implode(', ', $vals) . ');');
  }
}

