<?php

$host_ip = '77.222.60.36';
$host_domain = 'st.2v0.ru';
$base_dir = dirname(__DIR__);

$conf = <<<EOF
server 
{   
    listen {$host_ip}:80;
    server_name {$host_domain};

    log_format stats '\$arg_pid,\$time_local,\$args';
    
    location /save
    {
      error_log /var/log/nginx/{$host_domain}.error.log warn;
      access_log /var/log/nginx/{$host_domain}.access.log stats;
      empty_gif;
    }

    location /js/bit_stats.js 
    {
      alias {$base_dir}/www/media/bit_stats.js;
      access_log off;
    }
    
    localtion /
    {
      return 444;
    }

    access_log off;
}

EOF;

echo $conf;
