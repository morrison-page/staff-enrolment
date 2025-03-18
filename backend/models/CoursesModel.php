<?php

namespace Backend\Models;

use Backend\Interfaces\ICrudModel;
use Backend\Classes\Database;

require_once __DIR__ . '/../interfaces/ICrudModel.php';
require_once __DIR__ . '/../classes/Database.php';

/**
 * Class CoursesModel
 *
 * Handles database operations related to courses
 * This class implements CRUD operations for managing courses
 *
 * @package Backend\Models
 */
class CoursesModel implements ICrudModel {

    /**
     * Retrieves all courses from the database
     *
     * This method fetches all courses along with their course details such as
     * course title, date, duration, maximum attendees, and total attendees
     *
     * @return array List of courses with their details
     */
    public static function all() {
        $db = new Database();
        $sql = "
            SELECT
                cd.course_id,
                cd.course_title,
                DATE_FORMAT(cd.course_date, '%d/%m/%Y') AS course_date,
                cd.course_duration,
                cd.max_attendees,
                COUNT(ed.user_id) AS total_attendees,
                cd.description
            FROM course_details cd
            LEFT JOIN enrolment_details ed 
                ON cd.course_id = ed.course_id
            GROUP BY cd.course_id
        ";
        $result = $db->executeQuery($sql);
        return $result;
    }

    /**
     * Finds a specific course by its ID
     *
     * This method retrieves the details of a course identified by its unique course ID
     *
     * @param string $id The course ID
     * @return array Course details
     */
    public static function find($id) {
        $db = new Database();
        $sql = "
            SELECT
                cd.course_id,
                cd.course_title,
                DATE_FORMAT(cd.course_date, '%d/%m/%Y') AS course_date,
                cd.course_duration,
                cd.max_attendees,
                COUNT(ed.user_id) AS total_attendees,
                cd.description
            FROM course_details cd
            LEFT JOIN enrolment_details ed ON cd.course_id = ed.course_id
            WHERE
                cd.course_id = ?
        ";
        $params = ['s', $id];
        $result = $db->executeQuery($sql, $params);
        return $result;
    }

    /**
     * Creates a new course
     *
     * This method inserts a new course record into the database using the provided data
     *
     * @param array $data The course data including title, date, duration, etc.
     * @return bool The result of the insert operation
     */
    public static function create($data) {
        $db = new Database();
        $sql = "
            INSERT INTO course_details (
                course_title,
                course_date,
                course_duration,
                max_attendees,
                description
            ) VALUES (?, STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?)
        ";
        $params = ['ssiis',
            $data['course_title'],
            $data['course_date'],
            $data['course_duration'],
            $data['max_attendees'],
            $data['description']
        ];
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }

    /**
     * Updates a course
     *
     * This method updates an existing course's details in the database based on the provided ID and data
     *
     * @param string $id The course ID to be updated
     * @param array $data The new course data
     * @return bool The result of the update operation
     */
    public static function update($id, $data) {
        $db = new Database();
        $sql = "
            UPDATE course_details
            SET
                course_title = ?,
                course_date = STR_TO_DATE(?, '%d/%m/%Y'),
                course_duration = ?,
                max_attendees = ?,
                description = ?
            WHERE
                course_id = ?
        ";
        $params = ['ssiiss',
            $data['course_title'],
            $data['course_date'],
            $data['course_duration'],
            $data['max_attendees'],
            $data['description'],
            $id
        ];
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }

    /**
     * Deletes a course by its ID
     *
     * This method deletes a course record from the database using the specified course ID
     *
     * @param string $id The course ID to be deleted
     * @return bool The result of the delete operation
     */
    public static function delete($id) {
        $db = new Database();
        $sql = "
            DELETE FROM course_details
            WHERE course_id = ?
        ";
        $params = ['s', $id];
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }
}

?>