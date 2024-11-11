<?php

namespace Backend\Controllers;

require '../models/EnrollmentsModel.php';
use Backend\Models\EnrollmentsModel as Enrollments;

class UsersController {
    public function index() {
        $courses = Enrollments::all();
        $this->render($courses);
    }

    public function show($id) {
        $course = Enrollments::find($id);
        $this->render($course);
    }

    public function create() {
        Enrollments::create($_POST);
        $this->render(['status' => 'success', 'message' => 'Course created successfully']);
    }

    public function update($id) {
        Enrollments::update($id, $_POST);
        $this->render(['status' => 'success', 'message' => 'Course updated successfully']);
    }

    public function delete($id) {
        Enrollments::delete($id);
        $this->render(['status' => 'success', 'message' => 'Course deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>