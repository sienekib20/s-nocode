<?php

class render
{
  public function loadView($view, $args)
  {
    extract($args);

    require_once __DIR__ . "/../../views/$view.html";
  }
}
