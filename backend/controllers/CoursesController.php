<?php

namespace Backend\Controllers;

require '../models/CoursesModel.php';
use Backend\Models\CoursesModel as Courses;

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
        // Create a single course logic
        Courses::create($_POST);
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