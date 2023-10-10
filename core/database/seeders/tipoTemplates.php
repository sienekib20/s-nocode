<?php

namespace core\database\seeders;

use core\database\Database;

class tipoTemplates
{
    public function insert()
    {
        Database::table('tipo_templates')->insert([
            [
                'tipo_template' => 'Landing Page'
            ],
            [
                'tipo_template' => 'Dashboard'
            ],
            [
                'tipo_template' => 'Outro'
            ],
        ], true);
    }
}
