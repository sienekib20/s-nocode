<?php

namespace core\database\default;

class QueryBuilder
{
    public static function build_insert_query($table, $fields = [])
    {
        $query = "INSERT INTO {$table}(";

        foreach (array_keys($fields) as $key => $field) {

            $query .= ($key != count($fields) - 1) ? "`{$field}`, " : "`{$field}`) VALUES(" . str_pad('', (count($fields) * 2) - 1, "?,", STR_PAD_LEFT) . ")";
        }

        return $query;
    }

    public static function build_update_query($table, $where, $binds = [])
    {
        $query = "UPDATE {$table} SET ";


        foreach (array_keys($binds) as $key => $field) {

            $query .= ($key != count($binds) - 1) ? "{$field} = ?, " : "{$field} = ? WHERE " . array_keys($where)[0] . ' = ?';
        }

        return $query;
    }

    public static function build_delete_query($table, $column)
    {
        return "DELETE FROM {$table} WHERE {$column} = ?";
    }

    public static function build_select_query($table, $where = null, $fields)
    {
        $query =  "SELECT {$fields} FROM {$table}";

        if (is_null($where)) {

            return $query;
        }

        $query .= " $where";

        return $query;
    }
}
