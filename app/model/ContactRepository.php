<?php


class ContactRepository implements RepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function save(array $data)
    {
        $stmt = $this->db->prepare("INSERT INTO contacts (name, email, message, category) VALUES (:name, :email, :message, :category)");
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'category' => $data['category'] ?? 'Geral'
        ]);
        return $this->db->lastInsertId();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findAll()
    {
        $stmt = $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM contacts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
