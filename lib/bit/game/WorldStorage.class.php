<?php

bit_lazy_class('GameWorldInterface', 'bit/game/GameWorldInterface.interface.php');

class WorldStorage
{
  protected $_storage;

  function __construct($storage)
  {
    $this->_storage = $storage;
  }

  function load($id, $db_only = false, $not_store = false)
  {
    // load from storage
    if($this->_storage->isLocked($id))
      return false;
    if(!$db_only && ($world = $this->_storage->get($id)) && $world instanceof GameWorldInterface)
      return $world;

    // load from db 
    $world = new GameWorld($id);
    if(!$world->tryLoad())
      return false;
    $world->setObjectVersion($world->getDbVersion());
    if(!$not_store)
      $this->_storage->set($id, $world);
    return $world;
  }

  function store(GameWorldInterface $world)
  {
    $id = $world->getId();
    if($this->_storage->isLocked($id))
      return false;
    $this->_storage->set($id, $world);
    return true;
  }

  function save(GameWorldInterface $world, $remove_after_save = false)
  {
    $id = $world->getId();
    if(!$this->_storage->tryLock($id, 600))
      return false;

    $conn = null;
    dbal()->execute('START TRANSACTION');
    try
    {
      $conn = $world->getDbConnection();
      $conn->execute('START TRANSACTION');

      $last_version = $world->getDbVersion();
      if($world->getObjectVersion() !== $last_version)
        throw new Exception('World is not last version. Version: '.$world->getObjectVersion().', version in db: '.$last_version);
      $world->updateDbVersion(++$last_version);
      $world->setObjectVersion($last_version);
      
      $world->save($this);
        
      if($remove_after_save)
        $this->_storage->remove($id);
      else
        $this->_storage->set($id, $world);
      dbal()->execute('COMMIT');
      $conn->execute('COMMIT');
      $this->_storage->unlock($id);
      return true;
    } catch (Exception $e) {
      dbal()->safeExecute('ROLLBACK');
      if(null !== $conn)
        $conn->safeExecute('ROLLBACK');
      $this->_storage->remove($id);
      $this->_storage->unlock($id);

      bit_error_guard($e);
      return false;
    }
  }

  function saveOld($utime, $verbose = false)
  {
    $this->_saveOld($utime, $verbose);
  }
  
  function saveAll($verbose = false)
  {
    $this->_saveOld(-1, $verbose);
  }

  protected function _saveOld($utime, $verbose)
  {
    while(count($worlds_for_save = $this->_storage->fetchOldRecords($utime, 100)))
    {
      foreach($worlds_for_save as $id => $world)
      {
        try
        {
          if(!($world instanceof GameWorldInterface))
          {
            $this->_storage->remove($id);
            trigger_error('Is not valid world '.substr(serialize($world), 0, 100));
          }
          else
          {
            if(!$this->save($world, true))
              $this->_storage->remove($world->getId());
            if($verbose)
              printf("save ".$world->getId()."\n");
          }
          unset($world);
        } 
        catch (Exception $e) 
        {
          $this->_storage->remove($id);
          bit_error_guard($e);
        }
      }
    }
  }
}
