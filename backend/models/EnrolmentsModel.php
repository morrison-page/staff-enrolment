<?php

namespace Backend\Models;

use Backend\Interfaces\ICrudModel;
use Backend\Classes\Database;

require_once '../interfaces/ICrudModel.php';
require_once '../classes/Database.php';

class EnrolmentsModel {
    public static function all() {
        // Logic to get all enrollments
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

    public static function delete($data) {
        // Logic to delete an enrollment
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