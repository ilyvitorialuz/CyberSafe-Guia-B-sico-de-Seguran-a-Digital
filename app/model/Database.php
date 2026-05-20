<?php


class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = Config::getInstance();
        $driver = $config->get('DB_DRIVER', 'sqlite');
        $dbName = $config->get('DB_NAME', 'cybersafe.db');
        $host = $config->get('DB_HOST', 'localhost');
        $user = $config->get('DB_USER', 'root');
        $pass = $config->get('DB_PASS', '');

        try {
            if ($driver === 'sqlite') {
                $this->connection = new PDO("sqlite:" . __DIR__ . "/../database/" . $dbName);
            } else {
                $this->connection = new PDO("$driver:host=$host;dbname=$dbName", $user, $pass);
            }
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
