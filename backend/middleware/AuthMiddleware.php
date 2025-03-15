<?php

namespace Backend\Middleware;

require_once '../classes/JWT.php';

use Backend\Classes\JWT;

class AuthMiddleware {
    public static function validateToken() {
        if (!isset($_COOKIE['token'])) {
            http_response_code(401); // unauthorised
            self::render(['status' => 'error', 'message' => 'Missing Cookie Token']);
            exit;
        }

        $token = $_COOKIE['token'];

        try {
            $payload = JWT::decode($token);
            if ($payload['exp'] < time()) {
                http_response_code(401);
                self::render(['status' => 'error', 'message' => 'Token has expired']);
                exit;
            }
            return;
        } catch (\Exception $e) {
            http_response_code(401);
            self::render(['status' => 'error', 'message' => 'Dodgey token brev']);
            exit;
        }
    }
    
    private static function render($data) {
        require '../views/json.php';
    }
}

?>