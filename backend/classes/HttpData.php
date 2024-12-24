<?php

namespace Backend\Classes;

class HttpData {
    public static function post() {
        return $_POST;
    }

    public static function put() {
        $rawData = file_get_contents('php://input');
        $delimiter = substr($rawData, 0, strpos($rawData, "\r\n"));
        $parts = array_slice(explode($delimiter, $rawData), 1);
        $data = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if (preg_match('/Content-Disposition: form-data; name="([^"]+)"/', $part, $matches)) {
                $name = $matches[1];
                $value = substr($part, strpos($part, "\r\n\r\n") + 4);
                if (preg_match('/ent-Disposition: form-data; name="[^"]+"/', trim($value), $matches)) {
                    $value = "";
                }
                $data[$name] = trim($value);
            }
        }
        return $data;
    }
}

?>