<?php

namespace Backend\Controllers;

require_once __DIR__ . '/../interfaces/ICrudController.php';
require_once __DIR__ . '/../classes/Sanitisation.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../classes/Utilities.php';
require __DIR__ . '/../models/CoursesModel.php';

use Backend\Interfaces\IcrudController;
use Backend\Models\CoursesModel as Courses;
use Backend\Classes\Sanitisation;
use Backend\Classes\Validation;
use Backend\Classes\Utilities;

/**
 * Courses CoursesController
 * 
 * Controller to handle CRUD operations for courses such 
 * as index, show, create, update, and delete
 *
 * @package Backend\Controllers
 */
class CoursesController implements IcrudController {

    /**
     * Get All Courses
     * 
     * Retrieves and displays all courses as JSON objects
     * If no courses are found, returns a 404 error
     * 
     * @return void
     */
    public function index() {
        $courses = Courses::all();

        if (empty($courses)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Courses Found']);
            return;
        }
        
        $this->render($courses);
    }

    /**
     * Get Single Course
     * 
     * Displays details of a specific course by ID.
     * Validates the course ID and returns an error if validation fails.
     * If the course is not found, returns a 404 error.
     *
     * @param string $id The ID of the course to retrieve.
     * @return void
     */
    public function show($id) {
        $data = ['course_id' => $id];

        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'course_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }
                
        $course = Courses::find($data['course_id']);
        
        if (empty($course)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'Course not found']);
            return;
        }

        $this->render($course);
    }

    /**
     * Creates a new course
     * 
     * Creates a new course based on the provided data
     * Validates the course data and returns an error if validation fails
     * If course creation fails, returns a 500 error
     * 
     * @return void
     */
    public function create() {
        $data = Utilities::deserialiseJson();

        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'course_title' => 'required|min:5|max:255',
            'course_date' => 'required|min:10|max:10|date',
            'course_duration' => 'required|min:1|max:11',
            'max_attendees' => 'required|min:1|max:11',
            'description' => 'required|min:2|max:100',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $success = Courses::create($data);
        
        if (!$success) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Course could not be created']);
            return;
        }
        
        $this->render(['status' => 'success', 'message' => 'Course created successfully']);
    }

    /**
     * Updates a single course
     * 
     * Updates a course with new data based on the provided ID
     * Validates the course data and returns an error if validation fails
     * If course is not found, returns a 404 error
     * 
     * @param string $id The ID of the course to update
     * @return void
     */
    public function update($id) {
        $data = ['course_id' => $id] + Utilities::deserialiseJson();

        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'course_id' => 'required|min:43|max:43',
            'course_title' => 'required|min:5|max:255',
            'course_date' => 'required|min:10|max:10|date',
            'course_duration' => 'required|min:1|max:11',
            'max_attendees' => 'required|min:1|max:11',
            'description' => 'required|min:2|max:100',
        ]);
        
        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $course = Courses::find($data['course_id']);

        if (empty($course)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'Course not found']);
            return;
        }

        $success = Courses::update($data['course_id'], $data);
        
        if (!$success) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Course could not be updated']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Course updated successfully']);
    }

    /**
     * Deletes a single course
     * 
     * Deletes a course based on the provided ID
     * Validates the course ID and returns an error if validation fails
     * If course deletion fails, returns a 500 error
     * 
     * @param string $id The ID of the course to delete
     * @return void
     */
    public function delete($id) {
        $data = ['course_id' => $id];
        
        $data = Sanitisation::sanitise($data);
        
        $validation = (new Validation())->validate($data, [
            'course_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $success = Courses::delete($data['course_id']);
        
        if (!$success) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Course could not be deleted']);
            return;
        }
        
        $this->render(['status' => 'success', 'message' => 'Course deleted successfully']);
    }

    /**
     * Renders the response data as a JSON response
     * 
     * @param array $data The data to be sent in the response
     */
    private function render($data) {
        require __DIR__ . '/../views/json.php';
    }
}

?>
