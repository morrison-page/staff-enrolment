<?php

namespace Backend\Models;

use Backend\Interfaces\ICrudModel;
use Backend\Classes\Database;
use Dotenv\Dotenv;

require_once '../interfaces/ICrudModel.php';
require_once '../classes/Database.php';

class UsersModel implements ICrudModel {
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
        require_once __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        
        $binarySalt = random_bytes(16);
        $salt = bin2hex($binarySalt);
        $pepper = $_ENV['PASSWORD_PEPPER'];       
        $password = $data['password'];
        $seasonedPassword = $salt . $password . $pepper;
        $options = [
            'memory_cost' => 1<<18, // 256 MB
            'time_cost' => 12,      // 12 iterations
            'threads' => 4          // 4 threads
        ];
        $hashedPassword = password_hash($seasonedPassword, PASSWORD_ARGON2ID, $options);
        $accessLevel = 'user';

        $db = new Database();
        $query = "
            INSERT INTO user_details (
                user_id,
                email,
                first_name,
                last_name,
                password,
                salt,
                job_title,
                access_level
            ) VALUES (CONCAT('USER-', UUID()), ?, ?, ?, ?, ?, ?, ?)
        ";

        $params = ['sssssss',
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $hashedPassword,
            $binarySalt,
            $data['job_title'],
            $accessLevel,
        ];
        
        $result = $db->executeNonQuery($query, $params);
        return $result;
    }

    public static function update($id, $data) {
        // Logic to update a user
        $db = new Database();
        $query = "
            UPDATE user_details
            SET
                email = ?,
                first_name = ?,
                last_name = ?,
                job_title = ?,
                access_level = ?
            WHERE
                user_id = ?
        ";
        $params = ['ssssss',
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['job_title'],
            $data['access_level'],
            $id
        ];
        $result = $db->executeNonQuery($query, $params);
        return $result;        
    }

    public static function delete($id) {
        // Logic to delete a user
        $db = new Database();
        $query = "
            DELETE FROM user_details
            WHERE user_id = ?
        ";
        $params = ['s', $id];
        $result = $db->executeNonQuery($query, $params);
        return $result;
    }
}

?>