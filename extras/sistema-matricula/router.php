<?php
require_once 'controller.php';
require_once 'middleware.php';

class Router {
    public function route($metodo, $dados) {
        if ($metodo === 'GET') {
            // Requisições GET apenas exibem a view
            include 'view.php';
        } elseif ($metodo === 'POST') {
            // Requisições POST passam pelo Middleware antes do Controller
            Middleware::validarCampos($dados);
            
            $controller = new MatriculaController();
            $resultado = $controller->processarMatricula($dados);
            
            // Exibe a view novamente, mas agora com o resultado do processamento
            include 'view.php';
        } else {
            http_response_code(405);
            echo "Método não permitido.";
        }
    }
}
