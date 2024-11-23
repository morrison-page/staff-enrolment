<?php

namespace Backend\Models;

use Backend\Database;

class CoursesModel {
    public static function all() {
        // Implementation of getting all courses
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "
            SELECT
                course_id,
                course_title,
                DATE_FORMAT(course_date, '%d/%m/%Y') AS course_date,
                course_duration,
                max_attendees,
                description
            FROM
                course_details
            ";
        $result = mysqli_query($conn, $sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function find($id) {
        // Logic to find a course by ID
        $db = new Database();
        $conn = $db->getConnection();
        $sql = "
            SELECT
                course_id,
                course_title,
                DATE_FORMAT(course_date, '%d/%m/%Y') AS course_date,
                course_duration,
                max_attendees,
                description
            FROM
                course_details
            WHERE
                course_id = '{$id}'
            ";
        $result = mysqli_query($conn, $sql);
        return $result->fetch_assoc();
    }

    public static function create($data) {
        // Logic to create a new course
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("
            INSERT INTO course_details (
                course_title,
                course_date,
                course_duration,
                max_attendees,
                description
            ) VALUES (?, STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?)
        ");
        $stmt->bind_param('ssiis',
            $data['course_title'],
            $data['course_date'],
            $data['course_duration'],
            $data['max_attendees'],
            $data['description']
        );
        $stmt->execute();
    }

    public static function update($id, $data) {
        // Logic to update a course
    }

    public static function delete($id) {
        // Logic to delete a course
    }
}

?>