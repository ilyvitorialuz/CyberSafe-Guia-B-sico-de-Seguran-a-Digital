<?php
/**
 * Front Controller - Ponto de entrada único da aplicação.
 */

require_once 'router.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$dados = ($metodo === 'POST') ? $_POST : $_GET;

$router = new Router();
$router->route($metodo, $dados);
