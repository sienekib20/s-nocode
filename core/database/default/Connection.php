<?php

namespace core\database\default;

use PDO;
use PDOException;

class Connection
{

    public static function getConnectionInstance()
    {
        try {
            if (!($connection = new PDO(
                'mysql:host=localhost;dbname=nocode',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            ))) {
                throw new PDOException("Error while connecting to database", 1);
            }

            return $connection;
        } catch (PDOException $pdo) {
            repport('Database connection Error: ', $pdo, 500);
        }
    }

}
