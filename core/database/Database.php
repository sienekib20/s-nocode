<?php

namespace core\database;

use core\database\default\QueryBuilder;
use Exception;
use PDOException;

class Database
{
    private static $table_name;

    private static $connection;

    private static $where;

    public function __construct()
    {
        self::$connection = connection();
    }

    public static function table($table = null)
    {
        self::$table_name = $table;

        return database();
    }

    public function where($column, $value)
    {
        self::$where[$column] = $value;

        return $this;
    }

    public function delete()
    {
        try {
            $query = QueryBuilder::build_delete_query(self::$table_name, array_keys(self::$where)[0]);

            self::$connection->beginTransaction();

            $stmt = self::$connection->prepare($query);

            $stmt->bindValue(1, array_values(self::$where)[0]);

            $stmt->execute();



            return self::$connection->commit();

            //
        } catch (PDOException $ex) {

            self::$connection->rollBack();

            repport('Erro de remoção', $ex, 500);
        }
    }

    public function update($binds = [])
    {
        try {
            $query = QueryBuilder::build_update_query(self::$table_name, self::$where, $binds);

            self::$connection->beginTransaction();

            $stmt = self::$connection->prepare($query);

            for ($i = 1; $i <= count($values = array_values($binds)); $i++) {

                $stmt->bindValue($i, $values[$i - 1]);
            }

            $stmt->bindValue(count($values) + 1, array_values(self::$where)[0]);

            $stmt->execute();



            return self::$connection->commit();

            //
        } catch (PDOException $ex) {

            self::$connection->rollBack();

            repport('Erro de atualização', $ex, 500);
        }

        return $query;
    }

    public function insert($data = [], $seeder = false)
    {
        if ($seeder) {

            $count = database(self::$table_name)->get('*');

            if ($count == 0) {
                return array_map(function ($item) {
                    $this->executeInsert($item);
                }, $data);
            }
            return null;
        }

        return $this->executeInsert($data);
    }

    public function lastId()
    {
        $stmt = self::$connection->query("SELECT LAST_INSERT_ID()");
        $lastId = $stmt->fetchColumn();
        return $lastId;
    }

    private function executeInsert($data = [])
    {
        try {
            $query = QueryBuilder::build_insert_query(self::$table_name, $data);

            //var_dump($query);exit;

            self::$connection->beginTransaction();

            $stmt = self::$connection->prepare($query);

            for ($i = 1; $i <= count($values = array_values($data)); $i++) {
                $stmt->bindValue($i, $values[$i - 1]);
            }


            $stmt->execute();

            self::$connection->commit();

            return $this;

            //
        } catch (PDOException $ex) {

            self::$connection->rollBack();

            repport('Erro de registo', $ex, 500);
        }
    }

    public function get($fields = '*')
    {
        try {
            $query = QueryBuilder::build_select_query(self::$table_name, self::$where, $fields);

            $stmt = self::$connection->prepare($query);

            is_null(self::$where) ? $stmt->execute() : $stmt->execute(self::$where);



            return $stmt->fetchAll();

            //
        } catch (PDOException $ex) {

            repport('Erro de registo', $ex, 500);
        }
    }

    public static function select($query, $bind = [])
    {
        try {

            $stmt = self::$connection->prepare($query);

            $stmt->execute($bind);



            return $stmt->fetchAll();

            //
        } catch (PDOException $ex) {

            repport('Erro de seleção', $ex, 500);
        }
    }
}
