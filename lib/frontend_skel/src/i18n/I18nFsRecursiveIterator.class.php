<?php

class I18nFsRecursiveIterator extends lmbFsRecursiveIterator
{
  protected $_exclude_dirs = array();

  function setExcludeDirs($dirs)
  {
    $this->_exclude_dirs = array_map('realpath', $dirs);
  }

  function isFile()
  {
    if(!parent :: isFile())
      return false;
    $path = realpath($this->getPath());
    if(false !== strpos($path, '/.svn'))
      return false;
    foreach($this->_exclude_dirs as $dir)
      if($dir && (0 === strpos($path, $dir)))
      {
        echo "Exclude {$path}\n";
        return false;
      }
    return true;
  }
}
