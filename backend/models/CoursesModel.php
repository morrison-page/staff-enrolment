<?php

namespace Backend\Models;

require '../classes/Database.php';

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
    }

    public static function create($data) {
        // Logic to create a new course
    }

    public static function update($id, $data) {
        // Logic to update a course
    }

    public static function delete($id) {
        // Logic to delete a course
    }
}

?>