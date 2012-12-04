<?php

class MY_Loader extends CI_Loader
{
  static $HTML_ONLY_VIEWS = array(
    'shared/header',
    'shared/footer',
  );

  protected function request_is_format($format = null)
  {
    if (null === $format) {
      return false;
    }

    return isset($_GET['format']) && $format === $_GET['format'];
  }

  public function view($view, $vars = array(), $return = FALSE)
  {
    // XXX: yep, this is all pretty hacky. is there a better way to do this in CI?

    if ($this->request_is_format('json')) {
      // ignore header/footer calls for JSON calls
      if (in_array($view, self::$HTML_ONLY_VIEWS)) {
        if ($return) {
          return '';
        }

        return;
      }

      // TODO: search for "$view.json" on filesystem before attempting to load it
      $view .= '.json.php';
    }

    return parent::view($view, $vars, $return);
  }
}