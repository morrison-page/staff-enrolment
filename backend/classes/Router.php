<?php

namespace Backend\Classes;

include_once __DIR__ . '/../middleware/AuthMiddleware.php';

use Backend\Middleware\AuthMiddleware;

/**
 * Class Router
 *
 * A basic router to handle HTTP request routing, including middleware and dynamic route handling.
 * It allows adding routes with specific HTTP methods and dispatching requests to the appropriate callback.
 *
 * @package Backend\Classes
 */
class Router {
    /**
     * @var array An array of defined routes
     */
    private $routes = [];

    /**
     * @var string The prefix to be added to all routes
     */
    private $prefix = '';

    /**
     * Router constructor
     *
     * Sets up the route prefix. The default is an empty string
     *
     * @param string $prefix The prefix for the routes (optional)
     */
    public function __construct($prefix = '') {
        $this->prefix = $prefix;
    }

    /**
     * Adds a route to the router.
     *
     * This method allows you to add a route with a specific HTTP method, route path, and callback function
     *
     * @param string $method The HTTP method for the route (e.g., GET, POST)
     * @param string $route The route path
     * @param callable $callback The callback function to execute when the route matches
     */
    public function addRoute($method, $route, $callback) {
        $this->routes[] = [
            'method' => $method,
            'route' => rtrim($this->prefix . $route, '/'), // Remove trailing slash from route
            'callback' => $callback
        ];
    }

    /**
     * Dispatches the request to the correct route handler
     *
     * This method checks the requested URI and HTTP method, and then matches the route to call the associated callback
     * If the route is protected by authentication, the token is validated
     * If no route matches, a 404 error is returned
     */
    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/'); // Allow trailing slash in request URI by removing it
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Token validation is skipped for the /auth route
        if ($requestUri != $this->prefix . '/auth') {
            AuthMiddleware::validateToken();
        }

        // Loop through the routes to find a match
        foreach ($this->routes as $route) {
            // Replace dynamic route parameters with a regular expression pattern
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}|[a-zA-Z0-9]+-[a-f0-9-]+)', $route['route']);
            // Check if the request method and URI match the defined route
            if ($route['method'] === $requestMethod && preg_match('#^' . $pattern . '$#', $requestUri, $matches)) {
                array_shift($matches); // Remove the full match from the parameters
                call_user_func_array($route['callback'], $matches);
                return;
            }
        }

        // If no route matches, return a 404 error
        http_response_code(404); // Not Found
        echo json_encode(['status' => 'error', 'message' => '404 Not Found']);
    }
}

?>