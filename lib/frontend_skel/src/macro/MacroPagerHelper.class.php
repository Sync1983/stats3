<?php

lmb_require_class('limb/macro/src/tags/pager/lmbMacroPagerHelper.class.php');

class MacroPagerHelper extends lmbMacroPagerHelper
{
  function getPageUri($page = null)
  {
    if ($page == null)
      $page = $this->page_counter;

    $params = $_GET;
    unset($params['is_ajax']);
    unset($params['_']);

    if ($page <= 1)
      unset($params[$this->id]);
    else
      $params[$this->id] = $page;

    $flat_params = array();
    $this->toFlatArray($params, $flat_params);

    $query_items = array();
    foreach ($flat_params as $key => $value)
      $query_items[] = $key . '=' . urlencode($value);

    $query = implode('&', $query_items);

    if (empty($query))
      return $this->base_url;
    else
      return $this->base_url . '?' . $query;
  }
}
