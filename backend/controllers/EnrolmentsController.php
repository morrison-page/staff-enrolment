<?php

namespace Backend\Controllers;

require '../models/EnrolmentsModel.php';
use Backend\Models\EnrolmentsModel as Enrolments;

class EnrolmentsController {
    public function index() {
        $courses = Enrolments::all();
        $this->render($courses);
    }

    public function show($id) {
        $course = Enrolments::find($id);
        $this->render($course);
    }

    public function create() {
        Enrolments::create($_POST);
        $this->render(['status' => 'success', 'message' => 'Enrolment created successfully']);
    }

    public function update($id) {
        Enrolments::update($id, $_POST);
        $this->render(['status' => 'success', 'message' => 'Enrolment updated successfully']);
    }

    public function delete($id) {
        Enrolments::delete($id);
        $this->render(['status' => 'success', 'message' => 'Enrolment deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>