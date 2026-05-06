<?php

namespace App\Repository;

use App\Models\Contato;

/**
 * Interface IContatoRepository
 * 
 * Define o contrato que qualquer implementação de repositório de Contato deve seguir.
 * Garante que qualquer classe que implemente essa interface terá os métodos obrigatórios.
 * 
 * Princípio: Segregação de Interface (Interface Segregation Principle - ISP)
 * Cada método aqui representa uma operação de persistência de dados.
 */
interface IContatoRepository
{
    /**
     * Salva um novo contato ou atualiza um existente
     * 
     * @param Contato $contato O contato a ser salvo
     * @return int ID do contato salvo
     */
    public function save(Contato $contato): int;

    /**
     * Busca um contato pelo ID
     * 
     * @param int $id ID do contato
     * @return Contato|null Contato encontrado ou null
     */
    public function find(int $id): ?Contato;

    /**
     * Busca todos os contatos
     * 
     * @return array Lista de contatos
     */
    public function findAll(): array;

    /**
     * Busca contatos por email
     * 
     * @param string $email Email para buscar
     * @return array Lista de contatos com o email
     */
    public function findByEmail(string $email): array;

    /**
     * Busca contatos por categoria
     * 
     * @param string $categoria Categoria para buscar
     * @return array Lista de contatos da categoria
     */
    public function findByCategoria(string $categoria): array;

    /**
     * Deleta um contato pelo ID
     * 
     * @param int $id ID do contato
     * @return bool True se deletado com sucesso
     */
    public function delete(int $id): bool;

    /**
     * Conta o total de contatos
     * 
     * @return int Total de contatos
     */
    public function count(): int;
}
