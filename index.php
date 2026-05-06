<?php

/**
 * index.php - Bootstrap da Aplicação
 * 
 * Arquivo principal que atua como Container de Injeção de Dependência simplificado.
 * Responsável por:
 * - Carregar variáveis de ambiente
 * - Inicializar a conexão com banco de dados
 * - Instanciar Repositórios, Services e Controllers
 * - Rotear requisições para os controllers apropriados
 * - Tratar exceções globais
 * 
 * Princípio: Aqui é onde todas as dependências são "montadas" antes de
 * serem entregues aos Controllers.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Carrega o autoloader do Composer (ou cria um simples se não usar composer)
require_once __DIR__ . '/vendor/autoload.php';

// Carrega variáveis de ambiente
loadEnvFile(__DIR__ . '/.env');

// Inicia a sessão
session_start();

try {
    // ========== CONTAINER DE INJEÇÃO DE DEPENDÊNCIA ==========
    
    // 1. Conexão com Banco de Dados (Singleton)
    $pdo = \Config\Database::getInstance();

    // 2. Instancia Repositórios
    $contatoRepository = new \App\Repository\ContatoRepository($pdo);

    // 3. Instancia Services (recebe o Repositório)
    $contatoService = new \App\Service\ContatoService($contatoRepository);

    // 4. Instancia Controllers (recebe o Service)
    $contatoController = new \App\Controller\ContatoController($contatoService);

    // ========== ROTEAMENTO SIMPLES ==========
    
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    // Define as rotas
    if ($requestUri === '/contatos' && $method === 'GET') {
        $contatoController->index();
    } elseif ($requestUri === '/contatos' && $method === 'POST') {
        // Aplica middleware de validação
        $rules = [
            'nome' => ['required' => true, 'type' => 'text', 'min' => 3, 'max' => 100],
            'email' => ['required' => true, 'type' => 'email'],
            'mensagem' => ['required' => true, 'type' => 'textarea', 'min' => 10, 'max' => 5000],
            'categoria' => ['required' => false, 'type' => 'text', 'default' => 'Contato Geral'],
        ];

        // Sanitiza e valida os dados
        $_POST = \App\Middleware\ValidationMiddleware::validate($_POST, $rules);

        $contatoController->store();
    } elseif (preg_match('/^\/contatos\/(\d+)$/', $requestUri, $matches) && $method === 'GET') {
        $contatoController->show((int)$matches[1]);
    } elseif (preg_match('/^\/contatos\/(\d+)\/delete$/', $requestUri, $matches) && $method === 'POST') {
        $contatoController->destroy((int)$matches[1]);
    } elseif (preg_match('/^\/api\/contatos\/(\d+)$/', $requestUri, $matches) && $method === 'GET') {
        $contatoController->getJson((int)$matches[1]);
    } else {
        http_response_code(404);
        echo "Página não encontrada";
    }
} catch (\Config\Exception $e) {
    // Erro de configuração
    http_response_code(500);
    echo "Erro de configuração: " . $e->getMessage();
} catch (\Exception $e) {
    // Erro genérico
    http_response_code(500);
    $debug = $_ENV['APP_DEBUG'] ?? false;
    if ($debug) {
        echo "Erro: " . $e->getMessage() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        echo "Ocorreu um erro no sistema. Tente novamente mais tarde.";
    }
}

/**
 * Carrega variáveis de ambiente de um arquivo .env
 */
function loadEnvFile(string $path): void
{
    if (!file_exists($path)) {
        return; // Arquivo .env é opcional
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') === false || strpos($line, '#') === 0) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove aspas se existirem
        if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }
}
