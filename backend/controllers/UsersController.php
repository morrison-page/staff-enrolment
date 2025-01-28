<?php

namespace Backend\Controllers;

require_once '../interfaces/ICrudController.php';
require_once '../classes/Validation.php';
require_once '../classes/HttpData.php';
require '../models/UsersModel.php';

use Backend\Interfaces\IcrudController;
use Backend\Models\UsersModel as Users;
use Backend\Classes\HttpData;
use Backend\Classes\Validation;

class UsersController implements IcrudController {
    public function index() {
        $courses = Users::all();
        $this->render($courses);
    }

    public function show($id) {
        $course = Users::find($id);
        $this->render($course);
    }

    public function create() {
        $data = HttpData::post();
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'email' => 'required|min:4|max:255|email',
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'password' => 'required|min:6', // TODO: Revisit to add more complex password requirements
            'job_title' => 'required|min:2|max:100',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }
        
        Users::create($data);
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