<?php

namespace Backend\Models;

use Backend\Database;

class UsersModel {
    public static function all() {
        // Logic to get all users
        $db = new Database();
        $sql = "
            SELECT
                user_id,
                email,
                first_name,
                last_name,
                job_title,
                access_level,
                login_attempts,
                DATE_FORMAT(last_login_attempt, '%d/%m/%Y %H:%i:%s') as last_login_attempt
            FROM
                user_details
        ";
        $result = $db->executeQuery($sql);
        return $result;
    }

    public static function find($id) {
        // Logic to find a user by ID
        $db = new Database();
        $query = "
            SELECT
                user_id,
                email,
                first_name,
                last_name,
                job_title,
                access_level,
                login_attempts,
                DATE_FORMAT(last_login_attempt, '%d/%m/%Y %H:%i:%s') as last_login_attempt
            FROM
                user_details
            WHERE
                user_id = ? OR email = ?
        ";
        $params = ['ss', $id, $id];
        $result = $db->executeQuery($query, $params);
        return $result;
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