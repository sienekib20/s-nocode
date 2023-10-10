<?php

namespace core\classes\database;

use core\classes\database\presets\Sql;
use Exception;
use PDO;
use PDOException;

class Database
{
    private static $tableName;

    private static $link;

    public function __construct($table)
    {
        self::$tableName = $table;

        try {

            if (is_null(self::$link)) {
                self::$link = new PDO(
                    'mysql:host=localhost;dbname=framework',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    ]
                );
            }
        } catch (PDOException $ex) {

            response()->setHttpResponseCode(400);

            die('Connection error: ' . $ex->getMessage());
        }
    }

    public function insert()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function select($fields = '*', $params = [], $condition = [])
    {
        $query = Sql::buildSelectQuery(self::$tableName);
        var_dump(self::$link);exit;
    }

    public function get()
    {
    }

    public function where()
    {
    }

    public function find()
    {
    }
}
