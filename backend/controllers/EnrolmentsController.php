<?php

namespace Backend\Controllers;

require_once __DIR__ . '/../interfaces/ICrudController.php';
require_once __DIR__ . '/../classes/Sanitisation.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../classes/Utilities.php';
require __DIR__ . '/../models/EnrolmentsModel.php';

use Backend\Models\EnrolmentsModel as Enrolments;
use Backend\Classes\Sanitisation;
use Backend\Classes\Validation;
use Backend\Classes\Utilities;

class EnrolmentsController {
    public function index() {
        $enrolments = Enrolments::all();

        if (empty($enrolments)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Enrolments Found']);
            return;
        }

        $this->render($enrolments);
    }

    public function create() {
        $data = Utilities::deserialiseJson();
        
        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
            'course_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Enrolments::create($data);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be created']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Enrolment created successfully']);
    }

    public function delete() {       
        $data = Utilities::deserialiseJson();
        
        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
            'course_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Enrolments::delete($data);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be deleted']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Enrolment deleted successfully']);
    }

    private function render($data) {
        require __DIR__ . '/../views/json.php';
    }
}

?>