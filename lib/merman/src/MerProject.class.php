<?php

class MerProject
{
  protected $_fields;
  function __construct($conf)
  {
    $this->_fields = $conf;
  }

  function __get($name)
  {
    if(isset($this->_fields[$name]))
      return $this->_fields[$name];
  }

  function __set($name, $value)
  {
    if($name{0} == '_')
      throw new Exception('Property ' . $name . ' protected!');
    $this->_fields[$name] = $value;
  }

  function exportFromJs()
  {
    $data = $this->_fields;
    $data['is_protected'] = $this->isProtected();
    return $data;
  }

  function isProtected()
  {
    return file_exists($this->getProtectionFile()); 
  }

  function getProtectionFile()
  {
    return dirname($this->path) . '/' . $this->name . '.protect';
  }

  static function findAll($js = false)
  {
    $projects_dir = realpath(PROJECTS_DIR);
    if(!$projects_dir)
      throw new Exception('Bad projects dir: ' . PROJECTS_DIR);
    $projects = array();
    foreach(glob($projects_dir . '/*') as $dir)
    {
      if(!is_dir($dir) && !is_link($dir))
        continue;
      $project = self :: createForPath($dir, true);
      if(!$project)
        continue;
      if($js)
        $projects[] = $project->exportFromJs();
      else 
        $projects[] = $project;
    }
    if($js)
      $projects = json_encode($projects);
    return $projects;
  }

  static function createForPath($path, $force = false)
  {
    $path = realpath($path);
    if(!$path || (!$force && dirname($path) !== dirname(realpath(PROJECTS_DIR) . '/1')))
      return false;
    if(!is_dir($path . '/.hg/'))
      return false;
    $project = array(
      'name' => basename($path),
      'path' => $path
    );
    return new MerProject($project);
  }
}  
