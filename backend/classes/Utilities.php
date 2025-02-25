<?php

namespace Backend\Classes;

class Utilities {
    public static function deserialiseJson() {
        return json_decode(file_get_contents('php://input'), true);
    }
}

?>