<?php

lmb_require('limb/i18n/src/translation/lmbSourceDictionaryExtractor.class.php');
lmb_require('limb/core/src/lmbPHPTokenizer.class.php');
lmb_require('limb/i18n/src/translation/lmbI18NDictionary.class.php');

class MacroDictionaryExtractor extends lmbSourceDictionaryExtractor
{
  protected $tokenizer;

  function __construct()
  {
    $this->tokenizer = new lmbPHPTokenizer();
  }
	
	function extract($code, &$dictionaries = array(), $response = null)
  {
    $this->macro_extract($code, $dictionaries, $response);
    $this->php_extract($code, $dictionaries, $response);
  }
  
  function macro_extract($code, &$dictionaries = array(), $response = null)
  {
  	if(preg_match_all('~\{\{i(?:18n(?:t)?)?\s+(.+?)\s*(?:\/)?\}\}~', $code, $matches))
    {
      $tag_attributes_strings = $matches[1];
    	foreach($tag_attributes_strings as $item)
    	{
    		$domain = 'macro';
    		if(preg_match('~(?:message|m)=[\'"]([^\'"]+)[\'"]?~', $item, $atr_matches))
    		  $text = $atr_matches[1];
    		
    		if(preg_match('~(?:context|c)=[\'"]([^\'"]+)[\'"]?~', $item, $atr_matches))
    		  $domain = $atr_matches[1];
    		
    		if($response)
          $response->write("Maro template: '$text'@$domain\n");

        if(!isset($dictionaries[$domain]))
        {
          $dictionary = new lmbI18NDictionary();
          $dictionaries[$domain] = $dictionary;
        }
        else
          $dictionary = $dictionaries[$domain];

        $dictionary->add($text);
    	}
    }
  }
  
  function php_extract($code, &$dictionaries = array(), $response = null)
  {
    $this->tokenizer->input($code);

    while($token = $this->tokenizer->next())
    {
      if(is_array($token) && $token[0] == T_STRING && $token[1] == 'lmb_i18n')
      {
        $parenthesis = array();
        if($this->tokenizer->next() == "(")
        {
          $text_token = $this->tokenizer->next();
          if(!is_array($text_token) || $text_token[0] != T_CONSTANT_ENCAPSED_STRING)
            continue;

          array_push($parenthesis, 1);
          $text = trim($text_token[1], '"\'');

          //getting tokens until function closes its last )
          $buffer = array();
          while($parenthesis && $token = $this->tokenizer->next())
          {
            if($token == ")")
              array_pop($parenthesis);
            elseif($token == "(")
              array_push($parenthesis, 1);

            $buffer[] = $token;
          }

          $domain = 'default';
          if(sizeof($buffer) > 2)
          {
            $domain_token = $buffer[sizeof($buffer)-2];
            if(is_array($domain_token) && $domain_token[0] == T_CONSTANT_ENCAPSED_STRING)
              $domain = trim($domain_token[1], '"\'');
          }

          if($response)
            $response->write("Macro PHP source: '$text'@$domain\n");

          if(!isset($dictionaries[$domain]))
          {
            $dictionary = new lmbI18NDictionary();
            $dictionaries[$domain] = $dictionary;
          }
          else
            $dictionary = $dictionaries[$domain];

          $dictionary->add($text);
        }
      }
    }
  } 
}
