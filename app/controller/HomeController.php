<?php


class HomeController
{
    public function index()
    {
        // Headers for HTML content
        header('Content-Type: text/html; charset=UTF-8');
        require __DIR__ . '/../../view/main.php';
    }
}
