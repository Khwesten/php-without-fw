<?php

namespace Dao;

use Utils\CustomException;
use Utils\HttpStatusCode;
use Utils\MessageConstants;
use Utils\Response;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 08:15
 */
class DatabaseDao extends BaseDao
{
    function __construct()
    {
    }

    public function create()
    {
        $connection = $this->getWithoutDbname();

        $config = $this->getConfig();

        $sql = "CREATE DATABASE IF NOT EXISTS {$config['dbname']};
            GRANT ALL ON {$config['dbname']}.* TO '{$config['user']}'@'localhost';
            FLUSH PRIVILEGES;
            CREATE TABLE IF NOT EXISTS {$config['dbname']}.users (
                id INT(11) NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                nif INT(9),
                phone VARCHAR(50),
                PRIMARY KEY ( id )
            );
            CREATE TABLE IF NOT EXISTS {$config['dbname']}.address (
                id INT(11) NOT NULL AUTO_INCREMENT,
                userId INT(11),
                street VARCHAR(255),
                zipCode VARCHAR(8),
                place VARCHAR(255),
                country VARCHAR(3),
                PRIMARY KEY ( id ),
                FOREIGN KEY ( userId ) REFERENCES {$config['dbname']}.users( id )
            );";

        try {
            $connection->exec($sql);

            if ($connection->errorCode() == 00000) {
                throw new CustomException(HttpStatusCode::OK, MessageConstants::DATABASE_CREATED_SUCCESSFULLY_OR_ALREADY_EXIST);
            } else {
                $error = $connection->errorInfo();

                $stringMessage = $this->removeTrashFromError($error);
                throw new CustomException(HttpStatusCode::INTERNAL_SERVER_ERROR, $stringMessage);
            }
        } catch (\PDOException $e) {
            throw new CustomException(HttpStatusCode::INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    private function removeTrashFromError($error)
    {
        array_walk_recursive($error, function (&$val) { $val = strip_tags($val); });

        $stringMEssage = json_encode($error);
        $vowels = array('\\r', '\\n', '[', ']');
        $stringMEssage = str_replace($vowels, "", $stringMEssage);
        return str_replace('"', "'", $stringMEssage);
    }
}