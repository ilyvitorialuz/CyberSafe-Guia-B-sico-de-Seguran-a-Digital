<?php

namespace App\Controller;

use App\Exception\BusinessRuleException;
use App\Service\ContatoService;

/**
 * ContatoController
 * 
 * Controlador para gerenciar requisições relacionadas a Contatos.
 * Recebe suas dependências via Injeção de Dependência.
 * Mantém-se enxuto com try-catch simples.
 * 
 * Responsabilidades:
 * - Receber requisições HTTP
 * - Delegar lógica para o Service
 * - Tratar exceções de negócio
 * - Renderizar respostas (JSON ou View)
 * 
 * NÃO faz validações complexas aqui.
 */
class ContatoController
{
    private ContatoService $service;

    /**
     * Construtor com Injeção de Dependência
     * 
     * @param ContatoService $service Serviço de contatos
     */
    public function __construct(ContatoService $service)
    {
        $this->service = $service;
    }

    /**
     * Exibe a página de contatos
     */
    public function index(): void
    {
        try {
            $contatos = $this->service->listarContatos();
            $stats = $this->service->obterEstatisticas();

            // Renderiza a view passando dados
            require __DIR__ . '/../../resources/views/contatos/index.php';
        } catch (BusinessRuleException $e) {
            // Tratamento de erro de negócio
            $error = $e->getMessage();
            require __DIR__ . '/../../resources/views/errors/error.php';
        } catch (Exception $e) {
            // Tratamento de erro técnico (não mostra detalhes para o usuário)
            $error = 'Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.';
            require __DIR__ . '/../../resources/views/errors/error.php';
        }
    }

    /**
     * Armazena um novo contato
     * 
     * Espera dados POST: nome, email, mensagem, categoria (opcional)
     */
    public function store(): void
    {
        try {
            // Valida método HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new BusinessRuleException('Método HTTP não permitido.', 405);
            }

            // Captura dados do formulário
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $mensagem = $_POST['mensagem'] ?? '';
            $categoria = $_POST['categoria'] ?? 'Contato Geral';

            // Delega para o Service
            $contatoId = $this->service->criarContato($nome, $email, $mensagem, $categoria);

            // Redireciona após sucesso
            $this->redirect('/contatos?success=Contato criado com sucesso!');
        } catch (BusinessRuleException $e) {
            // Erro de negócio: mostra mensagem amigável
            $error = $e->getMessage();
            require __DIR__ . '/../../resources/views/errors/error.php';
        } catch (Exception $e) {
            // Erro técnico: não expõe detalhes
            $error = 'Erro ao processar o formulário. Tente novamente.';
            if ($_ENV['APP_DEBUG'] ?? false) {
                $error .= ' [' . $e->getMessage() . ']';
            }
            require __DIR__ . '/../../resources/views/errors/error.php';
        }
    }

    /**
     * Exibe um contato específico
     * 
     * @param int $id ID do contato
     */
    public function show(int $id): void
    {
        try {
            $contato = $this->service->obterContato($id);

            if ($contato === null) {
                throw new BusinessRuleException('Contato não encontrado.', 404);
            }

            require __DIR__ . '/../../resources/views/contatos/show.php';
        } catch (BusinessRuleException $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../../resources/views/errors/error.php';
        } catch (Exception $e) {
            $error = 'Erro ao buscar contato.';
            require __DIR__ . '/../../resources/views/errors/error.php';
        }
    }

    /**
     * Deleta um contato
     * 
     * @param int $id ID do contato
     */
    public function destroy(int $id): void
    {
        try {
            $this->service->deletarContato($id);
            $this->redirect('/contatos?success=Contato deletado com sucesso!');
        } catch (BusinessRuleException $e) {
            $error = $e->getMessage();
            require __DIR__ . '/../../resources/views/errors/error.php';
        } catch (Exception $e) {
            $error = 'Erro ao deletar contato.';
            require __DIR__ . '/../../resources/views/errors/error.php';
        }
    }

    /**
     * Retorna JSON para requisições AJAX
     * 
     * @param int $id ID do contato
     */
    public function getJson(int $id): void
    {
        header('Content-Type: application/json');

        try {
            $contato = $this->service->obterContato($id);

            if ($contato === null) {
                http_response_code(404);
                echo json_encode(['error' => 'Contato não encontrado']);
                return;
            }

            echo json_encode($contato->toArray());
        } catch (BusinessRuleException $e) {
            http_response_code(400);
            echo json_encode($e->getErrorResponse());
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao processar requisição']);
        }
    }

    /**
     * Redireciona para uma URL
     * 
     * @param string $url
     */
    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
