<?php

namespace Backend\Models;

use Backend\Database;

class EnrolmentsModel {
    public static function all() {
        // Logic to get all enrollments
        $db = new Database();
        $sql = "
            SELECT
                enrolment_id,
                user_id,
                course_id,
                DATE_FORMAT(enrolment_date, '%d/%m/%Y') as enrolment_date
            FROM
                enrolment_details
        ";
        $result = $db->executeQuery($sql);
        return $result;
    }

    public static function find($id) {
        // Logic to find an enrollment by ID
        $db = new Database();
        $sql = "
            SELECT
                enrolment_id,
                user_id,
                course_id,
                DATE_FORMAT(enrolment_date, '%d/%m/%Y') as enrolment_date
            FROM
                enrolment_details
            WHERE
                enrolment_id = ?
        ";
        $params = ['s', $id];
        $result = $db->executeQuery($sql, $params);
        return $result;
    }

    public static function create($data) {
        // Logic to create a new enrollment
    }

    public static function update($id, $data) {
        // Logic to update an enrollment
    }

    public static function delete($id) {
        // Logic to delete an enrollment
    }
}

?>