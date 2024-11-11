<?php

namespace Backend\Models;

require '../classes/Database.php';

use Backend\Database;

class UsersModel {
    public static function all() {
        // Logic to get all users
    }

    public static function find($id) {
        // Logic to find a user by ID
    }

    public static function create($data) {
        // Logic to create a new user
        $salt = random_bytes(16);
        $pepper = "superdupersecretpasswordpepper";
        $password = "Hello World!";
        $pepperedPassword = $password . $pepper;
        $saltedPassword = bin2hex($salt) . $pepperedPassword;
        $options = [
            'memory_cost' => 1<<18, // 256 MB
            'time_cost' => 12,      // 12 iterations
            'threads' => 4          // 4 threads
        ];
        $hashedPassword = password_hash($saltedPassword, PASSWORD_ARGON2ID, $options);

    }

    public static function update($id, $data) {
        // Logic to update a user
    }

    public static function delete($id) {
        // Logic to delete a user
    }
}

?>