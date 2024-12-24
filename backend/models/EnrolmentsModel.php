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

    public static function update($id, $data) {
        // Logic to update an enrollment
        $db = new Database();
        $sql = "
            UPDATE enrolment_details
            SET
                user_id = ?,
                course_id = ?,
                enrolment_date = STR_TO_DATE(?, '%Y-%m-%d')
            WHERE 
                enrolment_id = ?
        ";
        $params = ['ssss',
            $data['user_id'],
            $data['course_id'],
            $data['enrolment_date'],
            $id
        ];
        $result = $db->executeNonQuery($sql, $params);
        return $result;
    }

    public static function delete($id) {
        // Logic to delete an enrollment
    }
}

?>