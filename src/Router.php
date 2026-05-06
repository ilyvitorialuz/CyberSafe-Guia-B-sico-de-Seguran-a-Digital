<?php

namespace App;

class Router
{
    private $routes = [];

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function handle($method, $uri)
    {
        // Simple router matching
        $uri = parse_url($uri, PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            $pattern = str_replace('/', '\/', $route['path']);
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>\d+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $handler = $route['handler'];
                $controllerName = $handler[0];
                $methodName = $handler[1];

                // Simple DI for this prototype
                $controller = $this->resolve($controllerName);
                
                // Extract params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                return call_user_func_array([$controller, $methodName], $params);
            }
        }

        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Rota não encontrada.']);
    }

    private function resolve($controllerName)
    {
        if ($controllerName === \App\Controllers\AuthController::class) {
            $repo = new \App\Repositories\UserRepository();
            $service = new \App\Services\AuthService($repo);
            return new $controllerName($service);
        }

        if ($controllerName === \App\Controllers\ContactController::class) {
            $repo = new \App\Repositories\ContactRepository();
            $service = new \App\Services\ContactService($repo);
            return new $controllerName($service);
        }

        if ($controllerName === \App\Controllers\HomeController::class) {
            return new $controllerName();
        }

        return new $controllerName();
    }
}
