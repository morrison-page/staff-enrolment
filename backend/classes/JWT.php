<?php

namespace Backend\Classes;

use Dotenv\Dotenv;

/**
 * Class JWT
 *
 * This class provides functionality to create and decode JSON Web Tokens (JWT).
 * It uses a secret key and algorithm defined in the environment variables.
 * 
 * @package Backend\Classes
 */
class JWT {
    /**
     * @var string The secret key used for signing the JWT.
     */
    private static $secretKey;
    /**
     * @var string The algorithm used for signing the JWT (e.g., 'HS256').
     */
    private static $algorithm;

    /**
     * Static 'constructor' used to encapsulate initalisation logic 
     * 
     * Static intialisation of the JWT class by loading environment variables and setting
     * the secret key and algorithm from the environment. 
     */
    public static function initialize() {
        $jwt = new self();
        $jwt->loadEnvironmentVariables();
        self::$secretKey = getenv('JWT_SECRET_KEY');
        self::$algorithm = getenv('JWT_ALGORITHM');
    }

    /**
     * Loads Environment Variables fron .env file
     * 
     * This method loads the environment variables (JWT Secret and Algorithm)
     * from the .env file using the Dotenv library
     */
    private function loadEnvironmentVariables() {
        require __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    /**
     * Encodes the given payload into a JWT
     *
     * This method takes an array (the payload) and generates a JWT string.
     * The JWT consists of three parts: header, payload, and signature.
     * 
     * @param array $payload The data to be included in the token's payload.
     * @return string The encoded JWT.
     */
    public static function encode(array $payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload = json_encode($payload);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Decodes a given JWT and provides its payload
     *
     * This method validates the signature of the JWT and returns the payload
     * 
     * @param string $jwt The JWT string to decode
     * @return array The decoded payload (data stored in the token)
     * @throws \Exception If the token is invalid or the signature doesn't match
     */
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

    /**
     * Encodes data to a Base64 URL-safe string
     *
     * @param string $data The data to be encoded
     * @return string The Base64 URL-safe encoded string
     */
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decodes data to a Base64 URL-safe string
     *
     * @param string $data The data to be decoded
     * @return string The decoded data
     */
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

// Initalise the JWT class
JWT::initialize();

?>