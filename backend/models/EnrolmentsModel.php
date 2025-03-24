<?php

namespace Backend\Models;

use Backend\Classes\Database;

require_once __DIR__ . '/../classes/Database.php';

/**
 * Class EnrolmentsModel
 *
 * Handles database operations related to enrolments
 * This class implements CRUD operations for managing enrolments
 *
 * @package Backend\Models
 */
class EnrolmentsModel {

    /**
     * Retrieves all enrolments from the database
     *
     * This method fetches all enrolments along with their details such as
     * user information and course information
     *
     * @return array List of enrolments with their details
     */
    public static function all() {
        $db = new Database();
        $sql = "
            SELECT
                ed.enrolment_id,
                CONCAT(ud.first_name, ' ', ud.last_name) AS user,
                ud.user_id,
                ud.email,
                cd.course_id,
                cd.course_title,
                DATE_FORMAT(cd.course_date, '%d/%m/%Y') AS course_date
            FROM enrolment_details ed
            JOIN user_details ud ON ud.user_id = ed.user_id
            JOIN course_details cd ON cd.course_id = ed.course_id;
        ";
        $result = $db->executeQuery($sql);
        return $result;
    }

    /**
     * Creates a new enrolment
     *
     * This method inserts a new enrolment record into the database using the provided data
     *
     * @param array $data The enrolment data including user ID and course ID
     * @return bool The result of the insert operation
     */
    public static function create($data) {
        $db = new Database();
        $sql = "
            INSERT INTO enrolment_details (
                user_id,
                course_id
            ) VALUES (?, ?)
        ";
        $params = ['ss',
            $data['user_id'],
            $data['course_id']
        ];
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }

    /**
     * Deletes an enrolment
     *
     * This method deletes an enrolment record from the database using the specified user ID and course ID
     *
     * @param array $data The enrolment data including user ID and course ID
     * @return bool The result of the delete operation
     */
    public static function delete($data) {
        $db = new Database();
        $sql = "
            DELETE FROM enrolment_details
            WHERE
                user_id = ?
            AND
                course_id = ?
        ";
        $params = ['ss',
            $data['user_id'],
            $data['course_id']
        ];
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }
}

?>