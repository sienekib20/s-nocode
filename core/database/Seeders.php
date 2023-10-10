<?php

namespace core\database;

use core\database\seeders\tipo_templates;
use core\database\seeders\tipoTemplates;
use ReflectionClass;

class Seeders
{
    public function run()
    {
        $this->call([
            tipoTemplates::class,
        ]);
    }

    private function call($callback = [])
    {
        foreach ($callback as $class) {
            (new $class())->insert();
        }
    }
}
