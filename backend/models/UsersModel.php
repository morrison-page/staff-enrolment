<?php

namespace Backend\Models;

use Backend\Interfaces\ICrudModel;
use Backend\Classes\Database;
use Dotenv\Dotenv;

require_once __DIR__ . '/../interfaces/ICrudModel.php';
require_once __DIR__ . '/../classes/Database.php';

/**
 * Class UsersModel
 *
 * Handles database operations related to users
 * This class implements CRUD operations for managing users
 *
 * @package Backend\Models
 */
class UsersModel implements ICrudModel {

    /**
     * Retrieves all users from the database
     *
     * This method fetches all users along with their details such as
     * email, first name, last name, job title, access level, login attempts, and last login attempt
     *
     * @return array List of users with their details
     */
    public static function all() {
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

    /**
     * Finds a specific user by their ID or email
     *
     * This method retrieves the details of a user identified by their unique user ID or email
     *
     * @param string $id The user ID or email
     * @return array User details
     */
    public static function find($id) {
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
            WHERE
                user_id = ? OR email = ?
        ";
        $params = ['ss', $id, $id];
        $result = $db->executeQuery($sql, $params);
        return $result;
    }

    /**
     * Creates a new user
     *
     * This method inserts a new user record into the database using the provided data
     *
     * @param array $data The user data including email, first name, last name, password, job title, and access level
     * @return bool The result of the insert operation
     */
    public static function create($data) {
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

        $db = new Database();
        $sql = "
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
            $data['access_level'],
        ];
        
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }

    /**
     * Updates a user
     *
     * This method updates an existing user's details in the database based on the provided ID and data
     *
     * @param string $id The user ID to be updated
     * @param array $data The new user data
     * @return bool The result of the update operation
     */
    public static function update($id, $data) {
        $db = new Database();
        $sql = "
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
        $success = $db->executeNonQuery($sql, $params);
        return $success;        
    }

    /**
     * Deletes a user by their ID
     *
     * This method deletes a user record from the database using the specified user ID
     *
     * @param string $id The user ID to be deleted
     * @return bool The result of the delete operation
     */
    public static function delete($id) {
        $db = new Database();
        $sql = "
            DELETE FROM user_details
            WHERE user_id = ?
        ";
        $params = ['s', $id];
        $success = $db->executeNonQuery($sql, $params);
        return $success;
    }

    /**
     * Retrieves all courses a user is enrolled in
     *
     * This method fetches all courses along with their details that a specific user is enrolled in
     *
     * @param string $id The user ID
     * @return array List of courses with their details
     */
    public static function courses($id) {
        $db = new Database();
        $sql = "
            SELECT
                cd.course_id,
                cd.course_title,
                DATE_FORMAT(cd.course_date, '%d/%m/%Y') AS course_date,
                cd.course_duration,
                cd.max_attendees,
                (SELECT COUNT(*) 
                FROM enrolment_details ed2 
                WHERE ed2.course_id = cd.course_id) AS total_attendees,
                cd.description
            FROM
                course_details cd
            WHERE
                cd.course_id IN (
                    SELECT DISTINCT ed.course_id 
                    FROM enrolment_details ed 
                    WHERE ed.user_id = ?
                )
        ";
        $params = ['s', $id];
        $result = $db->executeQuery($sql, $params);
        return $result;
    }

    /**
     * Checks if a user exists by their email
     *
     * This method checks if a user exists in the database using the specified email
     *
     * @param string $email The user's email
     * @return bool The result of the existence check
     */
    public static function existsByEmail($email) {
        $db = new Database();
        $sql = "
            SELECT
                email
            FROM
                user_details
            WHERE
                email = ?
        ";
        $params = ['s', $email];
        $result = $db->executeScalarQuery($sql, $params);
        return $result;
    }
}

?>