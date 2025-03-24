<?php

namespace Backend\Models;

use Backend\Classes\Database;
use Dotenv\Dotenv;

require_once __DIR__ . '/../classes/Database.php';

/**
 * Class AuthModel
 *
 * Handles authentication-related operations
 * This class contains methods for user login, updating last login attempts, 
 * and retrieving authentication details based on the user's email
 *
 * @package Backend\Models
 */
class AuthModel {

    /**
     * Logs in a user by verifying the email and password
     *
     * This method retrieves the user record from the database using the email,
     * then validates the password using the stored salt and peppered password hash
     * 
     * @param array $data User credentials containing 'email' and 'password'
     * @return bool Returns true if login is successful, false otherwise
     */
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

    /**
     * Updates the last login timestamp of a user
     *
     * This method updates the `last_login_attempt` field in the database for the user
     * with the specified email
     * 
     * @param string $email The user's email
     * @return bool Returns the result of the update query
     */
    public static function updateLastLogin($email) {
        $db = new Database();
        $sql = "
            UPDATE user_details
            SET last_login_attempt = NOW()
            WHERE email = ?
        ";
        $params = ['s', $email];
        $success = $db->executeNonQuery($sql, $params);

        return $success;
    }

    /**
     * Retrieves authentication details for a user based on email
     *
     * This method fetches the `user_id` and `access_level` from the `user_details` table
     * for the user with the provided email address
     * 
     * @param string $email The user's email
     * @return array An associative array containing the user's `user_id` and `access_level`
     */
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