<?php
/*
 * Limb PHP Framework
 *
 * @link http://limb-project.com 
 * @copyright  Copyright &copy; 2004-2007 BIT(http://bit-creative.com)
 * @license    LGPL http://www.gnu.org/copyleft/lesser.html 
 */
lmb_require('limb/i18n/src/translation/lmbSourceDictionaryExtractor.class.php');

class JsDictionaryExtractor extends lmbSourceDictionaryExtractor
{
  protected $domain;
	
  function __construct($domain='js')
  {
  	$this->domain = $domain;
  }

	function extract($code, &$dictionaries = array(), $response = null)
  {
    if(preg_match_all('~i18n\([\'"]([^\'"]+)[\'"]\)?~', $code, $matches))
    {
      foreach($matches[1] as $index => $text)
      {

        if($response)
          $response->write("Js source: '$text'@{$this->domain}\n");

        if(!isset($dictionaries[$this->domain]))
        {
          $dictionary = new lmbI18NDictionary();
          $dictionaries[$this->domain] = $dictionary;
        }
        else
          $dictionary = $dictionaries[$this->domain];

        $dictionary->add($text);
      }
    }
  }
}
