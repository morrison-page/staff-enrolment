<?php

namespace Backend\Models;

use Backend\Interfaces\ICrudModel;
use Backend\Classes\Database;

require_once '../interfaces/ICrudModel.php';
require_once '../classes/Database.php';

class CoursesModel implements ICrudModel {
    public static function all() {
        // Implementation of getting all courses
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
            JOIN enrolment_details ed 
                ON cd.course_id = ed.course_id
            GROUP BY cd.course_id
            ";
        $result = $db->executeQuery($sql);
        return $result;
    }

    public static function find($id) {
        // Logic to find a course by ID
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
            JOIN enrolment_details ed ON cd.course_id = ed.course_id
            WHERE
                course_id = ?
            ";
        $params = ['s', $id];
        $result = $db->executeQuery($sql, $params);
        return $result;
    }

    public static function create($data) {
        // Logic to create a new course
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

    public static function update($id, $data) {
        // Logic to update a course
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

    public static function delete($id) {
        // Logic to delete a course
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