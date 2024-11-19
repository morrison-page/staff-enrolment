<?php

namespace Backend\Controllers;

require '../models/CoursesModel.php';
use Backend\Models\CoursesModel as Courses;
use DateTime;

class CoursesController {
    public function index() {
        // Get all courses logic
        $courses = Courses::all();
        $this->render($courses);
    }

    public function show($id) {
        // Get a single course logic
        $course = Courses::find($id);
        $this->render($course);
    }

    public function create() {
        // Define how to Sanitise Data
        $filters = [
            'course_title' => [
                'filter' => FILTER_CALLBACK,
                'options' => function($value) {
                    $value = htmlspecialchars(trim($value));
                    return is_string($value) && strlen($value) <= 255 ? $value : false;
                }
            ],
            'course_date' => [
                'filter' => FILTER_CALLBACK,
                'options' => function($value) {
                    $value = htmlspecialchars(trim($value));
                    $date = DateTime::createFromFormat('d/m/Y', $value);
                    return $date && $date->format('d/m/Y') === $value ? $value : false;
                }
            ],
            'course_duration' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 1,
                    'max_range' => 99999999999
                ]
            ],
            'max_attendees' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => [
                    'min_range' => 1,
                    'max_range' => 99999999999
                ]
            ],
            'description' => [
                'filter' => FILTER_CALLBACK,
                'options' => function($value) {
                    $value = htmlspecialchars(trim($value));
                    return is_string($value) ? filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : false;
                }
            ]
        ];

        // Grab and Sanitise Data
        $sanitisedData = filter_input_array(INPUT_POST, $filters);

        foreach ($sanitisedData as $key => $value) {
            if ($value === false || $value === null) {
                $this->render(['status' => 'error', 'message' => 'Missing/Invalid Value(s) in Request']);
                return;
            }
        }

        Courses::create($sanitisedData);
        $this->render(['status' => 'success', 'message' => 'Course created successfully']);
    }

    public function update($id) {
        // Update a single course logic
        Courses::update($id, $_POST);
        $this->render(['status' => 'success', 'message' => 'Course updated successfully']);
    }

    public function delete($id) {
        // Delete a single course logic
        Courses::delete($id);
        $this->render(['status' => 'success', 'message' => 'Course deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>