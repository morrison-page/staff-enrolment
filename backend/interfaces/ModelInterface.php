<?php

namespace Backend\Interfaces;

interface ModelInterface {
    public static function all();
    public static function find($id);
    public static function create($data);
    public static function update($id, $data);
    public static function delete($id);
}

?>