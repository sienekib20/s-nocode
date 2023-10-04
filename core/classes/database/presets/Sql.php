<?php

namespace core\classes\database\presets;

use Exception;

class Sql
{
    public static function buildSelectQuery($table, $fields = '*', $condition = [])
    {

        try {

            if (empty($condition)) {

                return "SELECT {$fields} FROM {$table}";
            }

            return "SELECT {$fields} FROM {$table} WHERE " . array_keys($condition)[0] . " = ?";
        } catch (Exception $ex) {
            //throw $th;
        }
    }
}
