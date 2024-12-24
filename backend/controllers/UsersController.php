<?php

namespace Backend\Controllers;

require '../models/UsersModel.php';
use Backend\Models\UsersModel as Users;
use Backend\Classes\HttpData;

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
        Users::create(HttpData::post());
        $this->render(['status' => 'success', 'message' => 'User created successfully']);
    }

    public function update($id) {
        Users::update($id, HttpData::put());
        $this->render(['status' => 'success', 'message' => 'User updated successfully']);
    }

    public function delete($id) {
        Users::delete($id);
        $this->render(['status' => 'success', 'message' => 'User deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>