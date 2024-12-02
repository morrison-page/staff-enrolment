<?php

namespace Backend;

class Router {
    private $routes = [];
    private $prefix = '';

    public function __construct($prefix = '') {
        $this->prefix = $prefix;
    }

    public function addRoute($method, $route, $callback) {
        $this->routes[] = [
            'method' => $method,
            'route' => rtrim($this->prefix . $route, '/'), // Remove trailing slash from route
            'callback' => $callback
        ];
    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/'); // Allow trailing slash in request URI by removing it
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})', $route['route']);
            if ($route['method'] === $requestMethod && preg_match('#^' . $pattern . '$#', $requestUri, $matches)) {
                array_shift($matches); // Remove the full match
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => '404 Not Found']);
    }
}

?>