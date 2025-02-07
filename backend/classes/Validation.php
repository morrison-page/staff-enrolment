<?php

namespace Backend\Classes;

use DateTime;

class Validation {
    private $data;
    private $rules = [];
    private $errors = [];

    public function validate($data, $rules) {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [];
        
        $extraFields = array_diff_key($data, $rules);
        foreach ($extraFields as $field => $value) {
            $this->errors[$field][] = "The {$field} field is not allowed."; // Check for extra fields not specified in the rules
        }

        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            foreach ($rulesArray as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];

                $method = 'validate' . ucfirst($ruleName);
                if (!method_exists($this, $method)) {
                    $this->errors['validation'] = "Validation rule {$ruleName} does not exist."; // Check for non-existent validation rules
                    return $this;
                }

                if (!$this->$method($field, ...$ruleParams)) {
                    $this->errors[$field][] = $this->getErrorMessage($ruleName, $field, $ruleParams);
                }
            }
        }

        return $this;
    }

    public function failed() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    private function getErrorMessage($rule, $field, $params) {
        $messages = [
            'required' => "The {$field} is required.",
            'email' => "The {$field} must be a valid email address.",
            'min' => "The {$field} must be at least " . (isset($params[0]) ? $params[0] : '') . " characters.",
            'max' => "The {$field} must be no more than " . (isset($params[0]) ? $params[0] : '') . " characters.",
            'date' => "The {$field} must be a valid date in the format dd/mm/yyyy.",
            'regex' => "The {$field} format is invalid.",
            'in' => "The {$field} must be one of: " . implode(', ', $params) . ".",
        ];
        return $messages[$rule];
    }

    // All methods below referenced in the validate method (@ Symbol Suppresses Errors)
    private function validateRequired($field) {
        return @isset($this->data[$field]) && !empty($this->data[$field]);
    }

    private function validateEmail($field) {
        return @filter_var($this->data[$field], FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateMin($field, $min) {
        return @strlen($this->data[$field]) >= $min;
    }

    private function validateMax($field, $max) {
        return @strlen($this->data[$field]) <= $max;
    }

    private function validateDate($field) {
        $date = @DateTime::createFromFormat('d/m/Y', $this->data[$field]);
        return $date && $date->format('d/m/Y') === $this->data[$field];
    }

    private function validateRegex($field, $pattern) {
        return @preg_match($pattern, $this->data[$field]);
    }

    private function validateIn($field, ...$values) {
        return @in_array($this->data[$field], $values);
    }
}

?>