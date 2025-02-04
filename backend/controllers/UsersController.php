<?php

namespace Backend\Controllers;

require_once '../interfaces/ICrudController.php';
require_once '../classes/Validation.php';
require_once '../classes/HttpData.php';
require '../models/UsersModel.php';

use Backend\Interfaces\IcrudController;
use Backend\Models\UsersModel as Users;
use Backend\Classes\Validation;
use Backend\Classes\HttpData;

class UsersController implements IcrudController {
    public function index() {
        $users = Users::all();

        if (empty($users)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Enrolments Found']);
            return;
        }

        $this->render($users);
    }

    public function show($id) {
        $data = ['user_id' => $id];
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $user = Users::find($id);
        
        if (empty($user)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'User not found']);
            return;
        }
        
        $this->render($user);
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
        
        $sucess = Users::create($data);
        
        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'User could not be created']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'User created successfully']);
    }

    public function update($id) {
        $data = ['user_id' => $id] + HttpData::put();
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
            'email' => 'required|min:4|max:255|email',
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            // TODO: Determine if password changing is allowed by admin
            'job_title' => 'required|min:2|max:100',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Users::update($id, $data);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'User could not be updated']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'User updated successfully']);
    }

    public function delete($id) {
        $data = ['user_id' => $id];
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41'
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }
        
        $sucess = Users::delete($id);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'User could not be deleted']);
            return;
        }
        
        $this->render(['status' => 'success', 'message' => 'User deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>