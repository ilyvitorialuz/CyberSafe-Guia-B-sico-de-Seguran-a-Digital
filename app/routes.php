<?php

use App\Router;
use App\Controllers\AuthController;
use App\Controllers\ContactController;

use App\Controllers\HomeController;

$router = new Router();

$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('POST', '/api/register', [AuthController::class, 'register']);
$router->add('POST', '/api/login', [AuthController::class, 'login']);
$router->add('POST', '/api/logout', [AuthController::class, 'logout']);

$router->add('POST', '/api/contacts', [ContactController::class, 'store']);
$router->add('GET', '/api/contacts', [ContactController::class, 'index']);
$router->add('DELETE', '/api/contacts/{id}', [ContactController::class, 'delete']);

return $router;
