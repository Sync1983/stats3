<?php


for($i = 1; $i < count($argv); $i++)
  repair_dba($argv[$i]);


function repair_dba($file_dba)
{
  echo "start {$file_dba}\n";
  if(!is_file($file_dba))
  {
    echo "file not found {$file_dba} \n";  
    return;
  }

  $res = dba_popen($file_dba, 'r-', 'db4');

  $copy_file = tempnam(dirname($file_dba), 'tmp_dba_');
  $res_copy = dba_popen($copy_file, 'c', 'db4');

  dba_firstkey($res);
  $count = 0;
  while($key = dba_nextkey($res))
  {
    dba_insert($key, dba_fetch($key, $res), $res_copy);
    $count++;
    if(!($count % 1000))
      printf("%d\n", $count);
  }
  dba_close($res);
  dba_close($res_copy);
  unlink($file_dba);
  rename($copy_file, $file_dba);

  printf("ok\n");
}
