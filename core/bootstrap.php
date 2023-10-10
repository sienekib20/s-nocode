<?php

require __DIR__ . '/support/helpers.php';
require root() . '/vendor/autoload.php';

require root() . '/routes/web.php';

seeders()->run();

$routes->dispatch();
