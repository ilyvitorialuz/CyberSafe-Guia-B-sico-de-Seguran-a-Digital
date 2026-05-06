<?php

namespace App\Service;

use App\Exception\BusinessRuleException;
use App\Models\Contato;
use App\Repository\IContatoRepository;

/**
 * ContatoService
 * 
 * Contém toda a lógica de negócio relacionada a Contatos.
 * NÃO instancia o Repositório internamente.
 * Recebe o Repositório via Injeção de Dependência no construtor.
 * 
 * Princípios aplicados:
 * - Dependency Injection (DI): Recebe suas dependências
 * - Single Responsibility Principle (SRP): Apenas regras de negócio
 * - Open/Closed Principle (OCP): Aberto para extensão via interface
 */
class ContatoService
{
    private IContatoRepository $repository;

    /**
     * Construtor com Injeção de Dependência
     * 
     * @param IContatoRepository $repository Repositório de contatos
     */
    public function __construct(IContatoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Cria um novo contato com validação de regras de negócio
     * 
     * @param string $nome Nome do contato
     * @param string $email Email do contato
     * @param string $mensagem Mensagem do contato
     * @param string $categoria Categoria do contato
     * @return int ID do contato criado
     * @throws BusinessRuleException Se violar alguma regra de negócio
     */
    public function criarContato(
        string $nome,
        string $email,
        string $mensagem,
        string $categoria = 'Contato Geral'
    ): int {
        // Validação: Nome não pode estar vazio
        if (empty(trim($nome))) {
            throw new BusinessRuleException(
                'O nome do contato não pode estar vazio.',
                1001,
                ['campo' => 'nome']
            );
        }

        // Validação: Email válido
        if (!$this->isEmailValido($email)) {
            throw new BusinessRuleException(
                'O email fornecido não é válido.',
                1002,
                ['campo' => 'email', 'valor' => $email]
            );
        }

        // Validação: Mensagem não pode estar vazia
        if (empty(trim($mensagem))) {
            throw new BusinessRuleException(
                'A mensagem não pode estar vazia.',
                1003,
                ['campo' => 'mensagem']
            );
        }

        // Validação: Comprimento mínimo da mensagem
        if (strlen(trim($mensagem)) < 10) {
            throw new BusinessRuleException(
                'A mensagem deve ter no mínimo 10 caracteres.',
                1004,
                ['campo' => 'mensagem', 'minimo' => 10]
            );
        }

        // Sanitização
        $nome = $this->sanitizarTexto($nome);
        $mensagem = $this->sanitizarTexto($mensagem);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Cria a instância do modelo
        $contato = new Contato($nome, $email, $mensagem, $categoria);

        // Persiste no banco de dados
        return $this->repository->save($contato);
    }

    /**
     * Obtém um contato pelo ID
     * 
     * @param int $id ID do contato
     * @return Contato|null
     * @throws BusinessRuleException Se o ID for inválido
     */
    public function obterContato(int $id): ?Contato
    {
        if ($id <= 0) {
            throw new BusinessRuleException(
                'ID de contato inválido.',
                1005,
                ['id' => $id]
            );
        }

        return $this->repository->find($id);
    }

    /**
     * Obtém todos os contatos
     * 
     * @return array Lista de contatos
     */
    public function listarContatos(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Obtém contatos por categoria
     * 
     * @param string $categoria
     * @return array
     * @throws BusinessRuleException Se categoria estiver vazia
     */
    public function listarPorCategoria(string $categoria): array
    {
        if (empty(trim($categoria))) {
            throw new BusinessRuleException(
                'Categoria não pode estar vazia.',
                1006,
                ['campo' => 'categoria']
            );
        }

        return $this->repository->findByCategoria(trim($categoria));
    }

    /**
     * Deleta um contato
     * 
     * @param int $id ID do contato
     * @return bool
     * @throws BusinessRuleException Se o ID for inválido
     */
    public function deletarContato(int $id): bool
    {
        if ($id <= 0) {
            throw new BusinessRuleException(
                'ID de contato inválido para exclusão.',
                1007,
                ['id' => $id]
            );
        }

        // Verifica se o contato existe antes de deletar
        $contato = $this->repository->find($id);
        if ($contato === null) {
            throw new BusinessRuleException(
                'Contato não encontrado para exclusão.',
                1008,
                ['id' => $id]
            );
        }

        return $this->repository->delete($id);
    }

    /**
     * Obtém estatísticas de contatos
     * 
     * @return array
     */
    public function obterEstatisticas(): array
    {
        return [
            'total' => $this->repository->count(),
            'dataUltimaAtualizacao' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Valida um email
     * 
     * @param string $email
     * @return bool
     */
    private function isEmailValido(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Sanitiza texto removendo tags HTML perigosas
     * 
     * @param string $texto
     * @return string
     */
    private function sanitizarTexto(string $texto): string
    {
        // Remove tags HTML
        $texto = strip_tags($texto);
        // Remove espaços extras
        $texto = preg_replace('/\s+/', ' ', $texto);
        return trim($texto);
    }
}
