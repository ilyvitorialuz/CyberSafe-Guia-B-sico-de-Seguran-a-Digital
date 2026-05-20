<?php


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
        $this->createModulesTable();
        $this->createQuizzesTable();
        $this->createProgressTable();
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

    private function createModulesTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS modules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT
        )";

        if (Config::getInstance()->get('DB_DRIVER') !== 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS modules (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT
            )";
        }
        $this->db->exec($sql);
    }

    private function createQuizzesTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS quizzes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            module_id INTEGER NOT NULL,
            question TEXT NOT NULL,
            option_a TEXT NOT NULL,
            option_b TEXT NOT NULL,
            option_c TEXT NOT NULL,
            option_d TEXT NOT NULL,
            correct_option CHAR(1) NOT NULL,
            FOREIGN KEY(module_id) REFERENCES modules(id)
        )";

        if (Config::getInstance()->get('DB_DRIVER') !== 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS quizzes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                module_id INT NOT NULL,
                question TEXT NOT NULL,
                option_a VARCHAR(255) NOT NULL,
                option_b VARCHAR(255) NOT NULL,
                option_c VARCHAR(255) NOT NULL,
                option_d VARCHAR(255) NOT NULL,
                correct_option CHAR(1) NOT NULL,
                FOREIGN KEY(module_id) REFERENCES modules(id)
            )";
        }
        $this->db->exec($sql);
    }

    private function createProgressTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS progress (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            module_id INTEGER NOT NULL,
            completed BOOLEAN DEFAULT 0,
            score INTEGER,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(user_id) REFERENCES users(id),
            FOREIGN KEY(module_id) REFERENCES modules(id)
        )";

        if (Config::getInstance()->get('DB_DRIVER') !== 'sqlite') {
            $sql = "CREATE TABLE IF NOT EXISTS progress (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                module_id INT NOT NULL,
                completed TINYINT(1) DEFAULT 0,
                score INT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(user_id) REFERENCES users(id),
                FOREIGN KEY(module_id) REFERENCES modules(id)
            )";
        }
        $this->db->exec($sql);
    }
}
