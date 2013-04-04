<?php

lmb_require('limb/i18n/common.inc.php');
lmb_require('src/i18n/DictionaryUpdater.class.php');
lmb_require('limb/i18n/src/translation/lmbQtDictionaryBackend.class.php');
lmb_require('limb/fs/src/lmbFs.class.php');

class UpdateDictionaryCli
{
  static function run($dry_run, $root)
  {
    $input_dir = realpath($root);
    if(!$input_dir)
    { 
      echo "Input directory is not valid\n";
      die();
    }  

    $output_dir = realpath($input_dir.'/i18n/translations');
    if(!$output_dir)
    { 
      echo "Output directory is not valid\n";
      die();
    }  

    $qt = new lmbQtDictionaryBackend();
    $qt->setSearchPath($output_dir);

    $util = new DictionaryUpdater($qt);

    if($dry_run)
      $util->dryrun($input_dir);
    else
      $util->updateTranslations($input_dir);
  }
}
