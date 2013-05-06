<?php

interface GameWorldInterface 
{
  function getId();
  function tryLoad();
  function save(WorldStorage $saver);

  function setObjectVersion($version);
  function getObjectVersion();
  function getDbVersion();
  function updateDbVersion($version);
}
