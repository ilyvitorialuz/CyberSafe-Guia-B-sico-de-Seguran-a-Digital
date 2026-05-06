<?php

namespace App;

use PDO;

class Migration
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function run()
    {
        $this->createUsersTable();
        $this->createContactsTable();
        echo "Migrations executadas com sucesso!\n";
    }

    private function createUsersTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        // Adjust for MySQL if needed
        $config = Config::getInstance();
        if ($config->get('DB_DRIVER') !== 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        }

        $this->db->exec($sql);
    }

    private function createContactsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            message TEXT NOT NULL,
            category TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        if (Config::getInstance()->get('DB_DRIVER') !== 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                category VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
        }

        $this->db->exec($sql);
    }
}
