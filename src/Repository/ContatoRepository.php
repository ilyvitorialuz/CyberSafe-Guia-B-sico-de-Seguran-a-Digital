<?php

namespace App\Repository;

use App\Models\Contato;
use PDO;
use PDOException;

/**
 * ContatoRepository
 * 
 * Implementação do repositório de Contato.
 * Responsável UNICAMENTE por operações de persistência no banco de dados.
 * Contém todo o SQL da aplicação.
 * 
 * Princípio de Responsabilidade Única (Single Responsibility Principle - SRP):
 * Essa classe só se preocupa com dados, não com lógica de negócio.
 */
class ContatoRepository implements IContatoRepository
{
    private PDO $pdo;

    /**
     * Construtor recebe a instância do PDO
     * 
     * @param PDO $pdo Instância de conexão do banco de dados
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Salva um novo contato ou atualiza um existente
     * 
     * @param Contato $contato
     * @return int ID do contato
     * @throws PDOException
     */
    public function save(Contato $contato): int
    {
        if ($contato->getId() === null) {
            return $this->insert($contato);
        } else {
            return $this->update($contato);
        }
    }

    /**
     * Insere um novo contato
     */
    private function insert(Contato $contato): int
    {
        $sql = 'INSERT INTO contatos (nome, email, mensagem, categoria, dataCriacao) 
                VALUES (:nome, :email, :mensagem, :categoria, :dataCriacao)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $contato->getNome(),
            ':email' => $contato->getEmail(),
            ':mensagem' => $contato->getMensagem(),
            ':categoria' => $contato->getCategoria(),
            ':dataCriacao' => $contato->getDataCriacao(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Atualiza um contato existente
     */
    private function update(Contato $contato): int
    {
        $sql = 'UPDATE contatos 
                SET nome = :nome, email = :email, mensagem = :mensagem, categoria = :categoria 
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $contato->getNome(),
            ':email' => $contato->getEmail(),
            ':mensagem' => $contato->getMensagem(),
            ':categoria' => $contato->getCategoria(),
            ':id' => $contato->getId(),
        ]);

        return $contato->getId();
    }

    /**
     * Busca um contato pelo ID
     * 
     * @param int $id
     * @return Contato|null
     */
    public function find(int $id): ?Contato
    {
        $sql = 'SELECT * FROM contatos WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch();
        return $data ? Contato::fromArray($data) : null;
    }

    /**
     * Busca todos os contatos
     * 
     * @return array
     */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM contatos ORDER BY dataCriacao DESC';
        $stmt = $this->pdo->query($sql);
        $data = $stmt->fetchAll();

        return array_map(fn($row) => Contato::fromArray($row), $data);
    }

    /**
     * Busca contatos por email
     * 
     * @param string $email
     * @return array
     */
    public function findByEmail(string $email): array
    {
        $sql = 'SELECT * FROM contatos WHERE email = :email ORDER BY dataCriacao DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        $data = $stmt->fetchAll();
        return array_map(fn($row) => Contato::fromArray($row), $data);
    }

    /**
     * Busca contatos por categoria
     * 
     * @param string $categoria
     * @return array
     */
    public function findByCategoria(string $categoria): array
    {
        $sql = 'SELECT * FROM contatos WHERE categoria = :categoria ORDER BY dataCriacao DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':categoria' => $categoria]);

        $data = $stmt->fetchAll();
        return array_map(fn($row) => Contato::fromArray($row), $data);
    }

    /**
     * Deleta um contato
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM contatos WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Conta total de contatos
     * 
     * @return int
     */
    public function count(): int
    {
        $sql = 'SELECT COUNT(*) as total FROM contatos';
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
}
