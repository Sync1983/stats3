<?php

lmb_require('src/i18n/I18nFsRecursiveIterator.class.php');
lmb_require('limb/i18n/src/translation/lmbPHPDictionaryExtractor.class.php');
lmb_require('src/i18n/MacroDictionaryExtractor.class.php');
lmb_require('src/i18n/JsDictionaryExtractor.class.php');
lmb_require('limb/i18n/src/translation/lmbFsDictionaryExtractor.class.php');
lmb_require('limb/i18n/src/translation/lmbI18NDictionary.class.php');
lmb_require('limb/i18n/src/translation/lmbQtDictionaryBackend.class.php');
lmb_require('limb/cli/src/lmbCliResponse.class.php');
lmb_require('limb/fs/src/lmbFsRecursiveIterator.class.php');
lmb_require('limb/fs/src/lmbFs.class.php');

class DictionaryUpdater
{
  protected $response;

  function __construct($backend, $response = null)
  {
    $this->backend = $backend;
    $this->response = $response ? $response : new lmbCliResponse();
  }

  function dryrun($source_dir)
  {
    $this->response->write("Dry-running in '$source_dir'...\n");

    $this->updateTranslations($source_dir, true);
  }

  function updateTranslations($source_dir, $dry_run = false)
  {
    $loader = new lmbFsDictionaryExtractor();
    
    $loader->registerFileParser('.js', new JsDictionaryExtractor());
    $loader->registerFileParser('.phtml', new MacroDictionaryExtractor());
    $loader->registerFileParser('.php', new lmbPHPDictionaryExtractor());

    $dicts = array();
    $iterator = new I18nFsRecursiveIterator($source_dir);
    $root = lmb_env_get('LIMB_DOCUMENT_ROOT');
    $iterator->setExcludeDirs(array($root . '/var', $root . '/media/', lmb_env_get('LIMB_VAR_DIR')));

    $this->response->write("======== Extracting translations from source ========\n");
    $loader->traverse($iterator, $dicts, $this->response);

    if(!$translations = $this->backend->loadAll())
    {
      $this->response->write("======== No existing translations found!(create them first) ========\n");
      return;
    }

    $this->response->write("======== Updating translations ========\n");

    foreach($translations as $locale => $domains)
    {
      foreach($domains as $domain => $old_dict)
      {
        if(isset($dicts[$domain]))
        {
          $this->response->write($this->backend->info($locale, $domain) . "...");

          $new_dict = $dicts[$domain]->merge($old_dict);
          if(!$dry_run)
          {
            $this->backend->save($locale, $domain, $new_dict);
            $this->response->write("updated\n");
          }
          else
            $this->response->write("skipped(dry-run)\n");
        }
      }
      foreach($dicts as $domain => $new_dict)
      {
        if(isset($domains[$domain]))
          continue;
        $this->response->write("WARNING!! Not found translation file for domain {$domain} and local {$locale}!\n");
      }
    }
  }
}

