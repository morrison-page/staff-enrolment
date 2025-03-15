<?php

namespace Backend\Models;

use Backend\Classes\Database;
use Dotenv\Dotenv;

require_once __DIR__ . '/../classes/Database.php';

class AuthModel {
	public static function login($data) {
        require_once __DIR__ . "/../vendor/autoload.php";
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        
        $db = new Database();
        $sql = "
            SELECT *
            FROM user_details
            WHERE LOWER(email) = LOWER(?)
        ";
        $params = ['s', $data['email']];
        $result = $db->executeQuery($sql, $params);

        if (count($result) < 0 || $result == null) {return false;}
        elseif (count($result) > 1) {return false;}
        
        $salt = bin2hex($result[0]['salt']);
        $pepper = $_ENV['PASSWORD_PEPPER'];
        $password = $data['password'];
        $seasonedPassword = $salt . $password . $pepper;
        $databasePassword = $result[0]['password'];
        $verifyPassword = password_verify($seasonedPassword, $databasePassword);

        if (!$verifyPassword) {return false;}

        return true;
    }

    public static function updateLastLogin($email) {
        $db = new Database();
        $sql = "
            UPDATE user_details
            SET last_login_attempt = NOW()
            WHERE email = ?
        ";
        $params = ['s', $email];
        $result = $db->executeNonQuery($sql, $params);

        return $result;
    }

    public static function getAuthDetailsByEmail($email) {
        $db = new Database();
        $sql = "
            SELECT 
                user_id,
                access_level
            FROM user_details
            WHERE LOWER(email) = LOWER(?)
        ";
        $params = ['s', $email];
        $result = $db->executeQuery($sql, $params);

        return $result[0];
    }
}

?>