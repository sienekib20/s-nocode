<?php

namespace core\database\default;

class MySQLGrammar
{
    public static function buildInsertQuery($table, $fields = [])
    {
        $attributes = array_keys($fields);

        $query = "INSERT INTO {$table} (";

        for ($i = 0; $i < count($attributes); $i++) {
            $query .=  ($i != count($attributes) - 1) ? "`$attributes[$i]`, " : "`$attributes[$i]`)";
        }

        $query .= " VALUES (";

        for ($i = 0; $i < count($attributes); $i++) {
            $query .=  ($i != count($attributes) - 1) ? "?, " : "?)";
        }

        return $query;
    }
}
