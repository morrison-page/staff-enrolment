<?php

namespace Backend\Controllers;

require_once '../interfaces/ICrudController.php';
require_once '../classes/Validation.php';
require_once '../classes/HttpData.php';
require '../models/EnrolmentsModel.php';

use Backend\Interfaces\IcrudController;
use Backend\Models\EnrolmentsModel as Enrolments;
use Backend\Classes\Validation;
use Backend\Classes\HttpData;

class EnrolmentsController implements IcrudController {
    public function index() {
        $enrolments = Enrolments::all();

        if (empty($enrolments)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'No Enrolments Found']);
            return;
        }

        $this->render($enrolments);
    }

    public function show($id) {
        $data = ['enrolment_id' => $id];
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'enrolment_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $enrolment = Enrolments::find($id);
        
        if (empty($enrolment)) {
            http_response_code(404); // Not Found
            $this->render(['status' => 'error', 'message' => 'Enrolment not found']);
            return;
        }
        
        $this->render($enrolment);
    }

    public function create() {
        $data = HttpData::post();
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'user_id' => 'required|min:41|max:41',
            'course_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Enrolments::create($data);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be created']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Enrolment created successfully']);
    }

    public function update($id) {
        $data = ['enrolment_id' => $id] + HttpData::put();
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'enrolment_id' => 'required|min:43|max:43',
            'user_id' => 'required|min:41|max:41',
            'course_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Enrolments::update($id, $data);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be updated']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Enrolment updated successfully']);
    }

    public function delete($id) {
        $data = ['enrolment_id' => $id];
        // TODO: Add Data sanitisation | htmlspecialchars && trim extra whitespace
        $validation = (new Validation())->validate($data, [
            'enrolment_id' => 'required|min:43|max:43',
        ]);

        if ($validation->failed()) {
            http_response_code(400); // Bad Request
            $this->render(['status' => 'error', 'message' => 'Validation errors', 'errors' => $validation->getErrors()]);
            return;
        }

        $sucess = Enrolments::delete($id);

        if (!$sucess) {
            http_response_code(500); // Internal Server Error
            $this->render(['status' => 'error', 'message' => 'Enrolment could not be deleted']);
            return;
        }

        $this->render(['status' => 'success', 'message' => 'Enrolment deleted successfully']);
    }

    private function render($data) {
        require '../views/json.php';
    }
}

?>