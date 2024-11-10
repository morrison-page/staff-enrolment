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
            'route' => $this->prefix . $route,
            'callback' => $callback
        ];
    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['route'] === $requestUri) {
                call_user_func($route['callback']);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => '404 Not Found']);
    }
}

?>