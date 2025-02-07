<?php

namespace Backend\Classes;

class Sanitisation {
    public static function sanitise($data) {
        $sanitisedData = [];
        foreach ($data as $key => $value) {
            $sanitisedData[$key] = @htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $sanitisedData;
    }
}

?>