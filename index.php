<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/autoload.php';

// CORS and Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Type: application/json');
    exit;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Sanitize inputs
Middleware::sanitize();

// Load routes and handle request
$router = require __DIR__ . '/app/router/routes.php';
$router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
