<?php
require_once(dirname(__FILE__).'/src/common.inc.php');

$m = new Mtask();
$m->run();

class Mtask
{
  protected $_listener;
  protected $_executing;
  protected $_params;

  function __construct()
  {
    $this->_listener = new HtmlListener();  
    $this->_executing = new Executing($this->_listener);
    $this->_params = array_merge($_GET, $_POST);
  }

  function run()
  {
    $mtask_name = 'mtask_' . $_REQUEST['cmd'];
    if(function_exists($mtask_name))
    {
      try
      {
        $mtask_name($this);
      } catch (Exception $e) {
        $this->error('Exception: '.$e->getMessage());
      };
      $this->_listener->message('OK!');
    }
    else
      $this->error('function ' . $mtask_name . ' not defined!');
  }

  function getListener()
  {
    return $this->_listener;
  }

  function getExecuting()
  {
    return $this->_executing;  
  }

  function exec($cmd, $params = array())
  {
    return $this->_executing->exec(Executing :: setParamsToCmd($cmd, $params));
  }

  function execEnsure($cmd, $params)
  {
    if(0 != ($r = $this->exec($cmd, $params)))
      return $this->error('Command "'.$cmd.'" execute failed! Return code: '.$r);
    return $r;
  }

  function getParam($name)
  {
    if(isset($this->_params[$name]))
      return $this->_params[$name];
    return null;
  }

  function getParamEnsure($name)
  {
    $v = $this->getParam($name);
    if(is_null($v))
      return $this->error('Param '.$name.' not transparent!');
    return $v;
  }

  function error($m)
  {
    $this->_listener->message('=========== ERROR!!!! '.$m.' ===========');
    exit(1);
  }

  function getSourceRepository($field = 'source', $path_only = true)
  {
    if(!$project = MerProject :: createForPath(PROJECTS_DIR . '/' . $this->getParamEnsure($field)))
      return $this->error('Source repository not found!');
    if($path_only)
      return $project->path;
    return $project;
  }
}

function mtask_clone($mtask)
{
  $source = $mtask->getSourceRepository();
  $target = $mtask->getParamEnsure('folder');
  if(!preg_match('/^[\da-z\_\-]+$/i', $target))
    return $mtask->error('Bad folder name: "' . $target.'"');
  $target = PROJECTS_DIR . '/' . $target;
  
  $mtask->execEnsure('hg clone $s $t', array('$s' => $source, '$t' => $target));
  $mtask->execEnsure('mkdir -p $', array('$' => $target . '/.hg/'));
  $mtask->execEnsure('cat $1 > $2', array('$1' => HGRC_DEFAULT, '$2' => $target . '/.hg/hgrc'));
  $mtask->execEnsure('hg -R $1 up', array('$1' => $target));
}

function mtask_move($mtask)
{
  $project = $mtask->getSourceRepository('source', false);
  if($project->isProtected())
    return $mtask->error('Project '.$project->name.' blocked from canges!');
  $source = $project->path;
  $target = $mtask->getParamEnsure('new_name');
  if(!preg_match('/^[\da-z\_\-]+$/i', $target))
    return $mtask->error('Bad folder name: "' . $target.'"');
  $target = PROJECTS_DIR . '/' . $target;
  if(file_exists($target))
    return $mtask->error('Folder "' . $target . '" already exists!');
  $mtask->execEnsure('mv $s $t', array('$s' => $source, '$t' => $target));
}

function mtask_protect($mtask)
{
  $project = $mtask->getSourceRepository('source', false);
  if($project->isProtected())
    return $mtask->error('Project '.$project->name.' already protected!');
  $mtask->execEnsure('date >> $1', array('$1' => $project->getProtectionFile()));
}

function mtask_delete($mtask)
{
  $project = $mtask->getSourceRepository('source', false);
  if($project->isProtected())
    return $mtask->error('Project '.$project->name.' blocked from removal!');
  $mtask->execEnsure('rm -r $1', array('$1' => $project->path));
}

function mtask_up($mtask)
{
  $path = $mtask->getSourceRepository('source');
  $mtask->execEnsure('hg up -R $p', array('$p' => $path));  
}
