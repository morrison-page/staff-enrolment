<?php

namespace Backend\Models;

use Backend\Interfaces\ICrudModel;
use Backend\Database;

class CoursesModel implements ICrudModel {
    public static function all() {
        // Implementation of getting all courses
        $db = new Database();
        $sql = "
            SELECT
                course_id,
                course_title,
                DATE_FORMAT(course_date, '%d/%m/%Y') AS course_date,
                course_duration,
                max_attendees,
                description,
                status
            FROM
                course_details
            ";
        $result = $db->executeQuery($sql);
        return $result;
    }

    public static function find($id) {
        // Logic to find a course by ID
        $db = new Database();
        $query = "
            SELECT
                course_id,
                course_title,
                DATE_FORMAT(course_date, '%d/%m/%Y') AS course_date,
                course_duration,
                max_attendees,
                description,
                status
            FROM
                course_details
            WHERE
                course_id = ?
            ";
        $params = ['s', $id];
        $result = $db->executeQuery($query, $params);
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
                course_date = STR_TO_DATE(?, '%Y-%m-%d'),
                course_duration = ?,
                max_attendees = ?,
                description = ?,
                status = ?
            WHERE
                course_id = ?
        ";
        $params = ['ssiisss',
            $data['course_title'],
            $data['course_date'],
            $data['course_duration'],
            $data['max_attendees'],
            $data['description'],
            $data['status'],
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