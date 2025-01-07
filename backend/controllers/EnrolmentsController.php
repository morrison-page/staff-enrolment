<?php

namespace Backend\Controllers;

require '../models/EnrolmentsModel.php';

use Backend\Interfaces\ControllerInterface;
use Backend\Models\EnrolmentsModel as Enrolments;
use Backend\Classes\HttpData;

class EnrolmentsController implements ControllerInterface {
    public function index() {
        $courses = Enrolments::all();
        $this->render($courses);
    }

    public function show($id) {
        $course = Enrolments::find($id);
        $this->render($course);
    }

    public function create() {
        Enrolments::create(HttpData::post());
        $this->render(['status' => 'success', 'message' => 'Enrolment created successfully']);
    }

    public function update($id) {
        Enrolments::update($id, HttpData::put());
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