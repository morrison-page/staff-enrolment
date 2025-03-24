<?php

namespace Backend\Classes;

/**
 * Class Sanitisation
 *
 * Provides methods to sanitise input data by trimming whitespace and converting special characters
 * to HTML entities to prevent potential XSS (Cross-Site Scripting) attacks
 *
 * @package Backend\Classes
 */
class Sanitisation {

    /**
     * Sanitises the provided data by trimming whitespace and converting special characters to HTML entities
     *
     * This method ensures that the input data is safe for use in HTML by removing unnecessary spaces
     * and encoding potentially dangerous characters, such as `<`, `>`, `&`, etc
     *
     * @param mixed $data The input data to be sanitised. Can be an array or string
     * @return array An array of sanitised data
     */
    public static function sanitise($data) {
        if (empty($data)){
            return [];
        }

        $sanitisedData = [];
        
        // Check if the data is an array and sanitise each element
        foreach ($data as $key => $value) {
            $sanitisedData[$key] = @htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        return $sanitisedData;
    }
}

?>
