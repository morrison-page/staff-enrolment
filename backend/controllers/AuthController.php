<?php

namespace Backend\Controllers;

require_once __DIR__ . '/../classes/Sanitisation.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../classes/Utilities.php';
require_once __DIR__ . '/../classes/JWT.php';
require __DIR__ . '/../models/AuthModel.php';

use Backend\Models\AuthModel as Auth;
use Backend\Classes\Sanitisation;
use Backend\Classes\Validation;
use Backend\Classes\Utilities;
use Backend\Classes\JWT;

class AuthController {
    public function login() {
        $data = Utilities::deserialiseJson();

        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'email' => 'required|min:4|max:255|email',
            'password' => 'required|min:6', // TODO: Revisit and add more password complexity requirements
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Auth::login($data);
        
        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Login failed']);
            return;
        }
     
        // Generate JWT token
        $authUser = Auth::getAuthDetailsByEmail($data['email']);
        $userId = $authUser['user_id'];
        $accessLevel = $authUser['access_level'];
        $email = $data['email'];
        $expiry = time() + (60 * 60); // Token valid for 1 hour

        $payload = [
            'user_id' => $userId,
            'access_level' => $accessLevel,
            'exp' => $expiry,
            'email' => $email
        ];

        $token = JWT::encode($payload);

        setcookie('token', $token, $expiry, "/", "127.0.0.1", false, true);

        // setcookie('token', $token, [
        //     "expires" => time() + 3600, // Expires in 1 hour
        //     "path" => "/",
        //     "domain" => "127.0.0.1:8080", // Change cookie domain when in prod
        //     "secure" => false, // Change to true when using HTTPS in prod
        //     "httponly" => true, // Prevents JavaScript access
        //     "samesite" => "Lax" // Use "None" and add secure = true in prod
        // ]);
        
        $this->render(['status' => 'success', 'message' => 'Logged in successfully']);
        return;
    }

    public function logout() {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $payload = JWT::decode($token);
            $payload['exp'] = time() - 3600; // Set expiry time to 1 hour before now
            $invalidToken = JWT::encode($payload);
            setcookie('token', $invalidToken, time() - 3600, "/", "127.0.0.1", false, true); // Set cookie with expired token
        }
        $this->render(['status' => 'success', 'message' => 'Logged out successfully']);
    }

    public function user() {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $payload = JWT::decode($token);
            unset($payload['exp']);
            unset($payload['email']);
            $this->render($payload);
        } else {
            http_response_code(401); // Unauthorised
            $this->render(['status' => 'error', 'message' => 'Unauthorised']);
        }
    }
    
    private function render($data) {
        require __DIR__ . '/../views/json.php';
    }
}

?>