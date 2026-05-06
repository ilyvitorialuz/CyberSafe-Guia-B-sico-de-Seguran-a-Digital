<?php

namespace App\Models;

/**
 * Contato Model
 * 
 * Representa uma entidade Contato no sistema.
 * Atua como um container de dados simples (Data Transfer Object).
 * Não contém lógica de banco de dados - essa responsabilidade fica com o Repository.
 * 
 * Propriedades:
 * - id: identificador único
 * - nome: nome do contato
 * - email: endereço de email
 * - mensagem: conteúdo da mensagem
 * - categoria: categoria do contato
 * - dataCriacao: data de criação
 */
class Contato
{
    private ?int $id = null;
    private string $nome;
    private string $email;
    private string $mensagem;
    private string $categoria;
    private ?string $dataCriacao = null;

    /**
     * Construtor
     */
    public function __construct(
        string $nome,
        string $email,
        string $mensagem,
        string $categoria = 'Contato Geral',
        ?int $id = null,
        ?string $dataCriacao = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->mensagem = $mensagem;
        $this->categoria = $categoria;
        $this->dataCriacao = $dataCriacao ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMensagem(): string
    {
        return $this->mensagem;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function getDataCriacao(): string
    {
        return $this->dataCriacao ?? date('Y-m-d H:i:s');
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setMensagem(string $mensagem): void
    {
        $this->mensagem = $mensagem;
    }

    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * Converte o modelo para array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'mensagem' => $this->mensagem,
            'categoria' => $this->categoria,
            'dataCriacao' => $this->dataCriacao,
        ];
    }

    /**
     * Cria uma instância a partir de um array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['nome'] ?? '',
            $data['email'] ?? '',
            $data['mensagem'] ?? '',
            $data['categoria'] ?? 'Contato Geral',
            $data['id'] ?? null,
            $data['dataCriacao'] ?? null
        );
    }
}
