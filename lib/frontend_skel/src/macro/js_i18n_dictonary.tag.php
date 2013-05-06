<?php

lmb_require('limb/web_app/src/macro/file_version.tag.php');

/**
 * @tag js:i18n_dictonary
 * @req_attributes dir,js_var
 * @restrict_self_nesting
 */  

class lmbJsI18nDictonaryMacroTag extends lmbFileVersionMacroTag
{  
  protected $_file_path = false;
  protected $_file_url = false;
  protected $_file_writed = false;

  protected function _generateContent($code)
  {
    $this->set('type', $this->has('type') ? $this->get('type') : 'js');
    parent :: _generateContent($code);
  }

  protected function _writeFile()
  {
    if($this->_file_writed)
      return;
    
    $toolkit = lmbToolkit :: instance();
    $translations = $toolkit->getDictionary($toolkit->getLocale(), 'js')->getTranslations();

    $url = lmbFs :: normalizePath($this->get('dir') . '/i18n_dictonary.'.$toolkit->getLocale().'.js');
    $path = $this->getRootDir() . '/' . $url;
    
    $script = '';
    $isset_property = 'window';
    $path_properties = explode('.', $this->get('js_var'));
    $last_property = array_pop($path_properties);
    foreach($path_properties as $property)
    {
      $isset_property .= '.' . $property;
      $script .= "{$isset_property}={$isset_property}||{};";
    }
    $script .= $isset_property . '.' . $last_property . '='.json_encode($translations).';';

    lmbFs :: safeWrite($path, $script);

    $this->_file_path = $path;
    $this->_file_url = $url;
    $this->_file_writed = true;
  }

  function getFilePath()
  {
    $this->_writeFile();
    return $this->_file_path;
  }

  function getFileUrl()
  {
    $this->_writeFile();
    return $this->_file_url;
  }
}
