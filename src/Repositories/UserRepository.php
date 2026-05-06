<?php

namespace App\Repositories;

use App\Database;
use PDO;

class UserRepository implements RepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function save(array $data)
    {
        if (isset($data['id'])) {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
            $stmt->execute($data);
            return $data['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            return $this->db->lastInsertId();
        }
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
