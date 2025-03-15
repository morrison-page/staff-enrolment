<?php

namespace Backend\Classes;

use Dotenv\Dotenv;

class JWT {
    private static $secretKey;
    private static $algorithm;

    public static function initialize() {
        $jwt = new self();
        $jwt->loadEnvironmentVariables();
        self::$secretKey = getenv('JWT_SECRET_KEY');
        self::$algorithm = getenv('JWT_ALGORITHM');
    }

    public function loadEnvironmentVariables() {
        require __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    public static function encode(array $payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload = json_encode($payload);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function decode($jwt) {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new \Exception('Invalid token');
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

        $header = json_decode(self::base64UrlDecode($base64UrlHeader), true);
        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);
        $signature = self::base64UrlDecode($base64UrlSignature);

        $validSignature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);

        if (!hash_equals($validSignature, $signature)) {
            throw new \Exception('Invalid token signature');
        }

        return $payload;
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

JWT::initialize();

?>