<?php

namespace App\Middleware;

class Middleware
{
    public static function sanitize()
    {
        // Sanitize GET and POST
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS) ?: $_GET;
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?: $_POST;
        
        // For JSON input
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
            // Sanitization would typically happen during json_decode or in Service
        }
    }

    public static function auth()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Não autorizado.']);
            exit;
        }
    }
}
