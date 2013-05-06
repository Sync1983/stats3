<?php

lmb_require('limb/log/src/lmbLogEntry.class.php');

class SimpleLogEntry extends lmbLogEntry
{
  function asText()
  {
    return $this->formatMessage() . ' ' . $this->formatBacktrace();
  }

  function formatMessage()
  {
    return $this->message . (count($this->params) ? "\nAdditional attributes: " . var_export($this->params, true) : '');
  }

  function formatBacktrace()
  {
    if(!$this->backtrace || !($backtrace = $this->backtrace->get()) || !count($backtrace))
      return;
    $str = '';
    foreach($backtrace as $item)
      $str .= "\n* " . $this->_formatBacktraceItem($item); 
    return "\n Backtrace: " . $str;
  }
  
  function asHtml()
  {
    return '<pre>' . htmlspecialchars($this->asText()) . '</pre>';
  }
  
  function _formatBacktraceItem($item)
  {
    $trace_string = '';

    if(isset($item['class']))
    {
      $trace_string .= $item['class'];
      $trace_string .= "::";
    }

    if(isset($item['function']))
    {
      $trace_string .= $item['function'];
      $trace_string .= "(";
    }

    if(isset($item['args']))
    {
      $sep = '';
      foreach($item['args'] as $arg)
      {
        $trace_string .= $sep;
        $sep = ', ';

        if(is_null($arg))
          $trace_string .= 'NULL';
        elseif(is_array($arg))
          $trace_string .= 'ARRAY[' . sizeof($arg) . ']';
        elseif(is_object($arg))
          $trace_string .= 'OBJECT:' . get_class($arg);
        elseif(is_bool($arg))
          $trace_string .= $arg ? 'TRUE' : 'FALSE';
        else
        {
          $trace_string .= '"';
          $trace_string .= htmlspecialchars(substr((string) @$arg, 0, 100));

          if(strlen($arg) > 100)
            $trace_string .= '...';

          $trace_string .= '"';
        }
      }
    }

    if(isset($item['function']))
    {
      $trace_string .= ")";
    }

    if(isset($item['file']))
    {
      $trace_string .= ' in "' . $item['file'] . '"';
      $trace_string .= " line ";
      $trace_string .= $item['line'];
    }

    return $trace_string;
  }
}
