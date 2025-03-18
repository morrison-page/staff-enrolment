<?php

namespace Backend\Middleware;

require_once __DIR__ . '/../classes/JWT.php';

use Backend\Classes\JWT;

/**
 * Class AuthMiddleware
 *
 * Middleware to handle authentication token validation
 * This class validates the JWT token stored in cookies and checks for its expiration
 *
 * @package Backend\Middleware
 */
class AuthMiddleware {

    /**
     * Validates the token from the cookie
     *
     * Checks if the token is present in the cookies, if it is valid, and if it is not expired
     * If any of these checks fail, it returns an error response with the corresponding status code
     * 
     * @return void
     */
    public static function validateToken() {
        if (!isset($_COOKIE['token'])) {
            http_response_code(401); // unauthorised
            self::render(['status' => 'error', 'message' => 'Missing Cookie Token']);
            exit;
        }

        $token = $_COOKIE['token'];

        try {
            $payload = JWT::decode($token);
            // Check if token has expired
            if ($payload['exp'] < time()) {
                http_response_code(401); // Unauthorised
                self::render(['status' => 'error', 'message' => 'Token has expired']);
                exit;
            }
            return;
        } catch (\Exception $e) {
            http_response_code(401); // Unauthorised
            self::render(['status' => 'error', 'message' => 'Dodgey token brev']);
            exit;
        }
    }
    
    /**
     * Renders the response data as a JSON response
     *
     * @param array $data The data to be sent in the response
     */
    private static function render($data) {
        require __DIR__ . '/../views/json.php';
    }
}

?>