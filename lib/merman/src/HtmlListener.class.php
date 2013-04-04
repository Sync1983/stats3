<?php

class HtmlListener 
{
  function message($string)
  {
    echo rtrim($string) . "\n"; 
    flush();
  }

  function __construct()
  {
    echo '
<html>
<head>
<script>
var autoscroll = false;
function scrolld()
{
  if(autoscroll)
    window.scrollTo(0,document.height);
}

function toggle_auto_scroll()
{
  autoscroll = !autoscroll;
}

setInterval(scrolld, 800);

</script>
</head>
<body>
<div style=\'border: 1px double black;position:fixed;top:0px;left:0px;width:100%;background-color:white\'>
<label for=\'autoscroll\'><small>Autoscroll</small></label>&nbsp;<input onclick=\'toggle_auto_scroll()\' type=\'checkbox\' id=\'autoscroll\'/>
</div>
<br>
<pre>';
  }
}
