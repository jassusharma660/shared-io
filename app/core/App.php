<?php

class App {

  protected $page = PAGE;
  protected $cmd = CMD;
  protected $params = PARAMS;
  protected $error = false;
  protected $no_access = array("header","footer");

  public function __construct() {
    $url = $this->parseUrl();
    $url[0] = $url[0]==ROOTINDEX?"home":$url[0];

    if(file_exists(VIEW.$url[0].'.php') && !in_array($url[0], $this->no_access)) {
      $this->page = $url[0];
      unset($url[0]);
    }
    else {
      $this->error = true;
      $this->echoError(404);
    }

    if(!$this->error) {
      $this->params = $url ? array_values($url):[];
      require_once VIEW.'header.php';
      require_once VIEW.$this->page.'.php';
      require_once VIEW.'footer.php';
    }

  }

  public function parseUrl() {
    if (isset($_GET['url'])) {
      $url = explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
      $this->sanitize($url);
      return $url;
    }
  }

  static public function echoError($errcode='', $errmsg='ERROR!') {
    $data = ['error_code'=>$errcode,'error_msg'=>$errmsg];
    switch ($errcode) {
      case '404':
        $data['error_msg'] = "PAGE NOT FOUND!";
        require_once VIEW.'error.php';
        break;

      default:
        require_once VIEW.'error.php';
        break;
    }
  }

  static private function cleanInput($input) {
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
    $output = preg_replace($search, '', $input);
    return $output;
  }

  static public function sanitize($input) {
      if (is_array($input)) {
          foreach($input as $var=>$val) {
              $output[$var] = App::sanitize($val);
          }
      }
      else {
          if (get_magic_quotes_gpc()) {
              $input = stripslashes($input);
          }
          $output  = App::cleanInput($input);
      }
      return $output;
    }

}
