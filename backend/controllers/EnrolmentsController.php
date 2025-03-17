<?php

namespace Backend\Controllers;

require_once __DIR__ . '/../classes/Sanitisation.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../classes/Utilities.php';
require __DIR__ . '/../models/EnrolmentsModel.php';

use Backend\Models\EnrolmentsModel as Enrolments;
use Backend\Models\CoursesModel as Courses;
use Backend\Models\UsersModel as Users;
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
       
        // Sending Email to user when enroled (Not functional in dev, will have to be tested in production)
        $user = Users::find($data['user_id']);
		$email = $user[0]['email'];
        $course = Courses::find($data['course_id']);
        $courseTitle = $course[0]['course_title'];
		$startDate = $course[0]['course_date'];
		
		$to = $email;
		$subject = "Enrolment to course - {$courseTitle}";
		$message = "You have sucessfuly been enroled on {$courseTitle} which will start on {$startDate}.";
 
        $sucess = mail($to, $subject, $message);

        $this->render(['status' => 'success', 'message' => 'Enrolment created successfully', 'email' => $sucess]);
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