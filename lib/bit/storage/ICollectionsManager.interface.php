<?php

interface IStoreCollectionsManager
{
  function create($id);
  function flushIfShemaChange($version);
  function flush();
}

interface IStoreCollection
{
  function get($id); 
  function getAll();
  function rawGetAll();
  function version($value);
  function setAll($rows);
  function flush();
}
