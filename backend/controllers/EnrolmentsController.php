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

/**
 * Class EnrolmentsController
 * 
 * Controller to handle enrolment-related operations such as 
 * viewing all enrolments, creating new enrolments, and deleting enrolments
 *
 * @package Backend\Controllers
 */
class EnrolmentsController {

    /**
     * Get All Enrolments
     * 
     * Retrieves and displays all enrolments as JSON objects
     * If no enrolments are found, returns a 404 error
     * 
     * @return void
     */
    public function index() {
        $enrolments = Enrolments::all();

        if (empty($enrolments)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Enrolments Found']);
            return;
        }

        $this->render($enrolments);
    }

    /**
     * Create Enrolment
     * 
     * Creates a new enrolment based on the provided data
     * Validates the enrolment data and returns an error if validation fails
     * If enrolment creation fails, returns a 500 error
     * Sends an email if the enrolment is created successfully
     * 
     * @return void
     */
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

        $success = Enrolments::create($data);

        if (!$success) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be created']);
            return;
        }

        // Send email notification to user upon successful enrolment (Prod only)
        $user = Users::find($data['user_id']);
        $email = $user[0]['email'];
        $course = Courses::find($data['course_id']);
        $courseTitle = $course[0]['course_title'];
        $startDate = $course[0]['course_date'];

        $to = $email;
        $subject = "Enrolment to course - {$courseTitle}";
        $message = "You have successfully been enrolled in {$courseTitle}, which will start on {$startDate}.";

        $success = mail($to, $subject, $message);

        $this->render(['status' => 'success', 'message' => 'Enrolment created successfully', 'email' => $success]);
    }

    /**
     * Delete Enrolment
     * 
     * Deletes an enrolment based on the provided user_id and course_id.
     * Validates the enrolment data and returns an error if validation fails.
     * If enrolment deletion fails, returns a 500 error.
     * 
     * @return void
     */
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

        $success = Enrolments::delete($data);

        if (!$success) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be deleted']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Enrolment deleted successfully']);
    }

    /**
     * Renders the response data as a JSON response.
     * 
     * @param array $data The data to be sent in the response.
     */
    private function render($data) {
        require __DIR__ . '/../views/json.php';
    }
}

?>
