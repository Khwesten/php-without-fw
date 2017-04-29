<?php

namespace Utils;
use Dao\DatabaseDao;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 26/04/2017
 * Time: 19:45
 */
class ConnectionFactory
{
    private $connection;
    private $config;

    public function get()
    {
        if (!$this->connection) {
            $this->fillConfig();

            $mysql = $this->config['mysql'];

            $dsn = "mysql:dbname={$mysql['dbname']};host={$mysql['host']}";
            $user = $mysql['user'];
            $password = $mysql['password'];

            try {
                $this->connection = new \PDO($dsn, $user, $password);
            } catch (\PDOException $e) {
                throw new CustomException(
                    500, "The database doesn't exist! Go to: '/index.php?type=controller&class=database&method=create'"
                );
            }

        }
        return $this->connection;
    }

    public function getWithoutDbname()
    {
        $this->fillConfig();

        $mysql = $this->config['mysql'];

        $dsn = "mysql:host={$mysql['host']}";
        $user = $mysql['user'];
        $password = $mysql['password'];

        $this->connection = new \PDO($dsn, $user, $password);

        return $this->connection;
    }

    protected function getConfig() {
        return $this->config['mysql'];
    }

    private function fillConfig() {
        $configFile = __DIR__ . "../../config/config.ini";

        if (!file_exists($configFile)) {
            throw new CustomException(500, "Create a config.ini file in '/config/'. Use '/config/model.config.ini' as parameter");
        }

        $this->config = parse_ini_file($configFile, true);
    }
}