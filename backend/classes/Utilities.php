<?php

namespace Backend\Classes;

/**
 * Class Utilities
 *
 * Provides utility methods for common operations
 *
 * @package Backend\Classes
 */
class Utilities {

    /**
     * Deserialises JSON data from the request body
     *
     * This method reads the raw input from the `php://input` stream and decodes it into a PHP associative array
     * It is commonly used to process JSON data sent via HTTP requests
     *
     * @return array|null The decoded associative array or null if the JSON is invalid
     */
    public static function deserialiseJson() {
        return json_decode(file_get_contents('php://input'), true);
    }
}

?>