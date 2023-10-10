<?php

class controller extends render
{
  public function index()
  {
    return $this->loadView('index', [
      'title' => 'Template'
    ]);
  }
}
