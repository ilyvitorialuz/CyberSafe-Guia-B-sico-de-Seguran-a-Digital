<?php

namespace App;

use App\Middleware\Middleware;

class App
{
    public function run()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $this->setHeaders();
            exit;
        }

        Middleware::sanitize();

        // Each controller is responsible for setting its own Content-Type header
        // (e.g., HTML for Home, JSON for API)
        $this->setCorsHeaders();

        $router = require __DIR__ . '/routes.php';
        $router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    private function setHeaders()
    {
        $this->setCorsHeaders();
        header('Content-Type: application/json');
    }

    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
}
