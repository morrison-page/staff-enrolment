<?php

namespace Backend\Controllers;

require_once '../interfaces/ICrudController.php';
require_once '../classes/Sanitisation.php';
require_once '../classes/Validation.php';
require_once '../classes/HttpData.php';
require '../models/CoursesModel.php';

use Backend\Interfaces\IcrudController;
use Backend\Models\CoursesModel as Courses;
use Backend\Classes\Sanitisation;
use Backend\Classes\Validation;
use Backend\Classes\HttpData;

class CoursesController implements IcrudController {
    public function index() {
        // Get all courses logic
        $courses = Courses::all();

        if (empty($courses)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Courses Found']);
            return;
        }
        
        $this->render($courses);
    }

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

    public function create() {
        $data = HttpData::post();

        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'course_title' => 'required|min:5|max:255',
            'course_date' => 'required', // TODO: Add date validation
            'course_duration' => 'required|min:1|max:11',
            'max_attendees' => 'required|min:1|max:11',
            'description' => 'required|min:2|max:100',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Courses::create($data);
        
        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Course could not be created']);
            return;
        }
        
        $this->render(['status' => 'success', 'message' => 'Course created successfully']);
    }

    public function update($id) {
        // Update a single course logic
        $data = ['course_id' => $id] + HttpData::put();

        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'course_id' => 'required|min:43|max:43',
            'course_title' => 'required|min:5|max:255',
            'course_date' => 'required', // TODO: Add date validation
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

        $sucess = Courses::update($data['course_id'], $data);
        
        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Course could not be updated']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Course updated successfully']);
    }

    public function delete($id) {
        // Delete a single course logic
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

        $sucess = Courses::delete($data['course_id']);
        
        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Course could not be deleted']);
            return;
        }
        
        $this->render(['status' => 'success', 'message' => 'Course deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>