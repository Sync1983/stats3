<?php

lmb_require_class('limb/filter_chain/src/lmbInterceptingFilter.interface.php');
lmb_require_class('limb/core/src/lmbErrorGuard.class.php');
lmb_require_class('limb/web_app/src/filter/lmbErrorHandlingFilter.class.php');

class ErrorHandlingFilter extends lmbErrorHandlingFilter
{
  function run($filter_chain)
  {
    $this->toolkit = lmbToolkit :: instance();
    set_error_handler(array($this, 'handleError'), E_ALL);
    lmbErrorGuard :: registerFatalErrorHandler($this, 'handleFatalError');
    set_exception_handler(array($this, 'handleException'));
    $filter_chain->next();
  }

  function isDebugMode()
  {
    return lmb_env_get('LIMB_APP_MODE') == 'devel';
  }

  function handleError($errno = 0, $errstr = '', $errfile = null, $errline = null, $errcontext = array())
  {
    if(error_reporting() == 0)
      return;
    $message = SimpleLog :: formatError($errno, $errstr, $errfile, $errline, $errcontext);
    if($this->isDebugMode())
      echo "\"\"<p><strong>ERROR: ".$message."</strong></p>";
    $this->toolkit->getLog()->notice($message);
  }

  function handleException($e)
  {
    for($i=0; $i < ob_get_level(); $i++)
      ob_end_clean();
    $this->toolkit->getResponse()->reset();
    if(function_exists('debugBreak'))
      debugBreak();
    $this->toolkit->getLog()->exception($e);
    if($this->isDebugMode() && $e instanceof lmbException)
      $this->_echoExceptionBacktrace($e);
    else
      $this->_echoErrorPage();
    exit(1);
  }

  function handleFatalError($error)
  {
    $this->toolkit->getLog()->error($error['message'] . (isset($error['file']) ? ' file ' . $error['file'] : '') . (isset($error['line']) ? ' line ' . $error['line'] : ''));
    $this->toolkit->getResponse()->reset();

    if($this->isDebugMode())
      $this->_echoErrorBacktrace($error);
    else
      $this->_echoErrorPage();

    exit(1);
  }
}
