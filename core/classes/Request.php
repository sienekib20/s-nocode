<?php

namespace core\classes;

class Request
{

  public function all()
  {
    return $_REQUEST;
  }

  public function path()
  {
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $current_path = trim($current_path, '/');

    return $current_path;
  }
}
