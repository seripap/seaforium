<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."helpers/library/htmlpurifier-4.3.0/library/HTMLPurifier.auto.php");
require_once(APPPATH."helpers/library/htmlpurifier-4.3.0/library/HTMLPurifier.func.php");


class HTMLPurifier_Filter_YouTube extends HTMLPurifier_Filter
{
  public $name = 'YouTube';

  public function preFilter($html, $config, $context) {

    $pre_regex = '#<object.*width="(\d+)".*height="(\d+)".*>.+?'.
      'http://www.youtube.com/((?:v|cp)/[A-Za-z0-9\-_=]+).+?</object>#s';
    $pre_replace = 'http://youtube.com/watch?v=\3&size=\1x\2</span>';

    $pre_regex2 = '#<object[^>]+>.+?'.
      'http://www.youtube.com/((?:v|cp)/[A-Za-z0-9\-_=]+).+?</object>#s';
    $pre_replace2 = 'http://youtube.com/watch?v=\1</span>';

    return preg_replace($pre_regex2, $pre_replace2,
                        preg_replace($pre_regex, $pre_replace, $html));
  }

  protected function armorUrl($url) {
    return str_replace('--', '-&#45;', $url);
  }
}

function purify($dirty_html)
{

  if (is_array($dirty_html)) {

      foreach ($dirty_html as $key => $val) {
        $dirty_html[$key] = purify($val);
      }

      return $dirty_html;
  }

  if (trim($dirty_html) === '') {
    return $dirty_html;
  }

  $config = HTMLPurifier_Config::createDefault();
  $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
  $config->set('AutoFormat.Linkify', true);
  $config->set('CSS.AllowTricky', true);
  $config->set('Filter.YouTube', true);
  $config->set('HTML.SafeObject', true);
  $config->set('Output.FlashCompat', true);
  $config->set('Output.Newline', '<br />');
  $def = $config->getHTMLDefinition(true);
  $def->addElement(
   'spoiler',   // name
   'Block',  // content set
   'Flow', // allowed children
   'Common', // attribute collection
   array()
  );

  return HTMLPurifier($dirty_html, $config);
}

?>