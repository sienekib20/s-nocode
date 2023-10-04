<?php

namespace app\models;

use core\support\Str;

abstract class Model
{
    private static function currentTableName()
    {
        return Str::getCurrentModelTableName(get_called_class());
    }

    public static function all($fields = '*')
    {

        return database(self::currentTableName())->select();
    }

    public static function insert($fields = [])
    {

        return database(self::currentTableName())->insert($fields);
    }

    public static function update($id, $fields = [])
    {

        return database(self::currentTableName())->update($fields);
    }

    public static function where($field, $value)
    {

        //return database($tableName)->select($fields);
        //return static;
    }

    public static function find($field)
    {

        //return database($tableName)->select($fields);

        //return static;
    }

    public static function get($fields = '*')
    {
    }
}
