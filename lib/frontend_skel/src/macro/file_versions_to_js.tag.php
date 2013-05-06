<?php

/**
 * @tag file_versions_to_js
 * @req_attributes js_var
 * @forbid_end_tag 
 */

class FileVersionToJsMacroTag extends lmbMacroTag
{
  protected $_code;

  protected function _generateContent($code)
  {
    $versions = array();
    $base_dir = lmb_env_get('LIMB_DOCUMENT_ROOT');
    $toolkit = lmbToolkit :: instance();
    $files = $toolkit->getConf('js')->get('versions');
    foreach($files as $pattern)
    {
      foreach(glob($base_dir . '/' . ltrim($pattern, '/')) as $file)
      {
        $file = substr($file, strlen($base_dir), strlen($file));
        $versions[ltrim($file, '/')] = $toolkit->addVersionToUrl($file);
      }
    }
    $script = '';
    $isset_property = 'window';
    $path_properties = explode('.', $this->get('js_var'));
    $last_property = array_pop($path_properties);
    foreach($path_properties as $property)
    {
      $isset_property .= '.' . $property;
      $script .= "{$isset_property}={$isset_property}||{};";
    }
    $script .= $isset_property . '.' . $last_property . '='.json_encode($versions).';';
    $code->writeHTML('<script type="text/javascript">' . $script . '</script>');
  }
}
