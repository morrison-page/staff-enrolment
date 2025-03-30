<?php

namespace Backend\Controllers;

require_once __DIR__ . '/../interfaces/ICrudController.php';
require_once __DIR__ . '/../classes/Sanitisation.php';
require_once __DIR__ . '/../classes/Validation.php';
require_once __DIR__ . '/../classes/Utilities.php';
require __DIR__ . '/../models/UsersModel.php';

use Backend\Interfaces\IcrudController;
use Backend\Models\UsersModel as Users;
use Backend\Classes\Sanitisation;
use Backend\Classes\Validation;
use Backend\Classes\Utilities;

/**
 * Class UsersController
 * 
 * Controller to handle CRUD operations for users such as index, show, create, update, delete,
 * and retrieving the courses a user is enrolled in
 *
 * @package Backend\Controllers
 */
class UsersController implements IcrudController {

    /**
     * Get All Users
     * 
     * Retrieves and displays all users as JSON objects
     * If no users are found, returns a 404 error
     * 
     * @return void
     */
    public function index() {
        $users = Users::all();

        if (empty($users)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Users Found']);
            return;
        }

        $this->render($users);
    }

    /**
     * Get Single User
     * 
     * Displays details of a specific user by ID
     * Validates the user ID and returns an error if validation fails
     * If the user is not found, returns a 404 error
     *
     * @param string $id The ID of the user to retrieve
     * @return void
     */
    public function show($id) {
        $data = ['user_id' => $id];
        
        $data = Sanitisation::sanitise($data);
        
        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $user = Users::find($data['user_id']);
        
        if (empty($user)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'User not found']);
            return;
        }
        
        $this->render($user);
    }

    /**
     * Creates a new user
     * 
     * Creates a new user based on the provided data
     * Validates the user data and returns an error if validation fails
     * If user creation fails, returns a 500 error
     * 
     * @return void
     */
    public function create() {
        $data = Utilities::deserialiseJson();
        
        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'email' => 'required|min:4|max:255|email',
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'job_title' => 'required|min:2|max:100',
            'access_level' => 'required|min:4|max:5|in:user,admin',
            'password' => 'required|min:6',
            'job_title' => 'required|min:2|max:100',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }
        
        $email = Users::existsByEmail($data['email']);

        if (!empty($email)) {
            http_response_code(409); // Conflict
            $this->render(['status' => 'error', 'message' => 'User already exists']);
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

    /**
     * Updates a user
     * 
     * Updates a user with new data based on the provided ID
     * Validates the user data and returns an error if validation fails
     * If user is not found, returns a 404 error
     * 
     * @param string $id The ID of the user to update
     * @return void
     */
    public function update($id) {
        $data = ['user_id' => $id] + Utilities::deserialiseJson();
        
        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
            'email' => 'required|min:4|max:255|email',
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'job_title' => 'required|min:2|max:100',
            'access_level' => 'required|min:4|max:5|in:user,admin',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $user = Users::find($data['user_id']);

        if (empty($user)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        $sucess = Users::update($data['user_id'], $data);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'User could not be updated']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'User updated successfully']);
    }

    /**
     * Deletes a user
     * 
     * Deletes a user based on the provided ID
     * Validates the user ID and returns an error if validation fails
     * If user deletion fails, returns a 500 error
     * 
     * @param string $id The ID of the user to delete
     * @return void
     */
    public function delete($id) {
        $data = ['user_id' => $id];
        
        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41'
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }
        
        $sucess = Users::delete($data['user_id']);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'User could not be deleted']);
            return;
        }
        
        $this->render(['status' => 'success', 'message' => 'User deleted successfully']);
    }

    /**
     * Get courses for a user
     * 
     * Retrieves the courses a user is enrolled in
     * If no courses are found, returns a 404 error
     * 
     * @param string $id The ID of the user to retrieve courses for
     * @return void
     */
    public function courses($id) {
        $data = ['user_id' => $id];
        
        $data = Sanitisation::sanitise($data);

        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41'
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $enrolments = Users::courses($data['user_id']);

        if (empty($enrolments[0]['total_attendees'])) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Courses Found']);
            return;
        }

        $this->render($enrolments);
    }

    /**
     * Renders the response data as a JSON response
     * 
     * @param array $data The data to be sent in the response
     */
    private function render($data) {
        require __DIR__ . '/../views/json.php';
    }
}

?>
