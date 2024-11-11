<?php

namespace Backend\Controllers;

require '../models/UsersModel.php';
use Backend\Models\UsersModel as Users;

class UsersController {
    public function index() {
        $courses = Users::all();
        $this->render($courses);
    }

    public function show($id) {
        $course = Users::find($id);
        $this->render($course);
    }

    public function create() {
        Users::create($_POST);
        $this->render(['status' => 'success', 'message' => 'Course created successfully']);
    }

    public function update($id) {
        Users::update($id, $_POST);
        $this->render(['status' => 'success', 'message' => 'Course updated successfully']);
    }

    public function delete($id) {
        Users::delete($id);
        $this->render(['status' => 'success', 'message' => 'Course deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>