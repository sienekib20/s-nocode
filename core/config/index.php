<?php

require __DIR__ . '/core/Core.php';
require __DIR__ . '/app/router/routes.php';

spl_autoload_register(function ($file) {
  if (file_exists(__DIR__ . "/app/utils/$file.php")) {
    require_once __DIR__ . "/app/utils/$file.php";
  }
});

$core = new Core();
$core->run($routes);
