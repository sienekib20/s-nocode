<?php

namespace core\database;

use core\database\default\Connection;
use core\database\default\MySQLGrammar;
use core\support\Timestamp;
use PDOException;

class DatabaseHelpers
{
    public static function insertIn($table, $fields = [])
    {
        try {

            connection()->beginTransaction();

            $stmt = connection()->prepare(MySQLGrammar::buildInsertQuery($table, $fields));

            $values = array_values($fields);

            for ($i = 1; $i <= count($values); $i++) {
                $stmt->bindValue($i, $values[$i - 1]);
            }

            $stmt->execute();

            return connection()->commit();

            //
        } catch (PDOException $pdo) {

            connection()->rollBack();

            repport('Erro de Criação', $pdo, 500);
        }
    }
}
