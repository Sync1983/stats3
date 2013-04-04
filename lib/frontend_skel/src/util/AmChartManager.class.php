<?php

class AmChartManager
{
  protected $_colors = array(
    'A66EDD',
    'F6BD0F',
    '75D94E',
    'FF6D3A',
    '285BFF',
    'F4EE42',
    'AC2266',
    '468E0D',
    '06AEFD',
    'c41E00',
  );

  static function create()
  {
    return new self;
  }

  function getNextColor()
  {
    if(!next($this->_colors))
      reset($this->_colors);
    return current($this->_colors);
  }
  
  protected function _comparsionXml()
  {
    return lmb_env_get('LIMB_DOCUMENT_ROOT') . '/shared/lib/am_comparsion.xml';
  }

  function oneChart($rows, $title = 'title')
  {
    $settings_file = $this->_comparsionXml();
    if(!file_exists($settings_file))
      return false;
    $settings = simplexml_load_file($settings_file);

    $xml = new SimpleXmlElement('<?xml version="1.0" encoding="UTF-8"?'.'><chart />');

    $set_graphs = $settings->addchild('graphs');
    $graphs = $xml->addchild('graphs');

    $color = '#'.$this->getNextColor();
    $graph = $set_graphs->addChild('graph');
    $graph->addAttribute('gid', 1);
    $graph->addChild('title', lmb_substr($title, 0, 30));
    $graph->addChild('color', $color);
    $graph->addChild('color_hover', $color);
    $graph->addChild('balloon_text_color', 'ffffff');
    $graph->addChild('balloon_text', '{value}');
    $graph->addChild('bullet', 'round');
    $graph->addChild('bullet_size', 6);
    $graph->addChild('line_width', 3);

    $graph = $graphs->addChild('graph');
    $graph->addAttribute('gid', 1);

    $sids = array();
    $scounter = 0;
    foreach($rows as $row)
    {
      $sid = $row['series'];
      if(!isset($sids[$sid]))
        $sids[$sid] = ++$scounter; 
      $graph->addChild('value', $row['value'])->addAttribute('xid', $sids[$sid]);
    }
    
    asort($sids);
    $series = $xml->addChild('series');
    foreach($sids as $title => $sid)
      $series->addChild('value', $title)->addAttribute('xid', $sid);
    return array('settings' => $settings->asXml(), 'data' => $xml->asXml(), 'type' => 'line', 'width' => '850', 'height' => 450);
  }

  function getSettingsAndData($rows, $gid_field, $titles = array(), $date_format = 'd M')
  {
    $settings_file = $this->_comparsionXml();
    if(!file_exists($settings_file))
      return false;
    $settings = simplexml_load_file($settings_file);

    $xml = new SimpleXmlElement('<?xml version="1.0" encoding="UTF-8"?'.'><chart />');

    $set_graphs = $settings->addchild('graphs');
    $graphs = $xml->addchild('graphs');

    $gids = array();
    $graphs_by_rgid = array();
    $gid_counter = 0;
    $sids = array();
    foreach($rows as $row)
    {
      $gid_real = $row[$gid_field];
      $sid = $row['date'];
      if(!isset($gids[$gid_real]))
      {
        $gid = ++$gid_counter;
        $gids[$gid_real] = $gid;

        $title = isset($titles[$gid_real]) ? $titles[$gid_real] : $gid_real;
        $color = '#'.$this->getNextColor();

        $graph = $set_graphs->addChild('graph');
        $graph->addAttribute('gid', $gid);
        $graph->addChild('title', lmb_substr($title, 0, 30));
        $graph->addChild('color', $color);
        $graph->addChild('color_hover', $color);
        $graph->addChild('balloon_text_color', 'ffffff');
        $graph->addChild('balloon_text', '{value}');
        $graph->addChild('bullet', 'round');
        $graph->addChild('bullet_size', 6);
        $graph->addChild('line_width', 3);

        $graph = $graphs->addChild('graph');
        $graph->addAttribute('gid', $gid);
        $graphs_by_rgid[$gid_real] = $graph;
      }
      else
      {
        $gid = $gids[$gid_real];
        $graph = $graphs_by_rgid[$gid_real];
      }
      $sids[$sid] = 1;

      $graph->addChild('value', $row['value'])->addAttribute('xid', $sid);
    }

    $sids = array_keys($sids);
    sort($sids);
    $series = $xml->addChild('series');
    foreach($sids as $sid)
      $series->addChild('value', date($date_format, $sid))->addAttribute('xid', $sid);
    return array('settings' => $settings->asXml(), 'data' => $xml->asXml(), 'type' => 'line', 'width' => '850', 'height' => 450);
  }
}
