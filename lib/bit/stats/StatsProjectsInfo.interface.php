<?php

interface StatsProjectsInfo
{
  function getProjectDir($id);  
  function hasProject($id);  
  function getBaseDir(); 
  function getNextProjectUid($current_uid); 
  function hasCounter($id, $name);
  function hasHiddenCounter($id, $name);
  function filterNotHiddenCounters($id, $names);
  function hasActiveStats($id);
  function normalizeReferrer($id, $name);
}
