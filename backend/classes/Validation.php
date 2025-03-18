<?php

namespace Backend\Classes;

use DateTime;

/**
 * Class Validation
 *
 * A class to validate input data based on rules such as required fields, email format, minimum/maximum length,
 * date format, regex matching, and predefined values (in)
 *
 * @package Backend\Classes
 */
class Validation {
    /**
     * @var array The input data to be validated.
     */
    private $data;

    /**
     * @var array The validation rules.
     */
    private $rules = [];

    /**
     * @var array The validation errors.
     */
    private $errors = [];

    /**
     * Validates input data against defined rules
     *
     * This method checks the data for each rule and stores any validation errors encountered
     *
     * @param array $data The input data to be validated
     * @param array $rules The validation rules to apply, where each field has one or more rules
     * @return $this The current instance for method chaining
     */
    public function validate($data, $rules) {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [];

        // Check for extra fields not specified in the rules
        $extraFields = array_diff_key($data, $rules);
        foreach ($extraFields as $field => $value) {
            $this->errors[$field][] = "The {$field} field is not allowed.";
        }

        // Validate fields according to the rules
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            foreach ($rulesArray as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];

                $method = 'validate' . ucfirst($ruleName);
                if (!method_exists($this, $method)) {
                    $this->errors['validation'] = "Validation rule {$ruleName} does not exist.";
                    return $this;
                }

                if (!$this->$method($field, ...$ruleParams)) {
                    $this->errors[$field][] = $this->getErrorMessage($ruleName, $field, $ruleParams);
                }
            }
        }

        return $this;
    }

    /**
     * Checks if validation has failed.
     *
     * @return bool Returns true if validation has failed, otherwise false.
     */
    public function failed() {
        return !empty($this->errors);
    }

    /**
     * Retrieves the validation errors.
     *
     * @return array An associative array of validation errors.
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Generates an error message for the validation rule.
     *
     * @param string $rule The rule that failed validation.
     * @param string $field The field being validated.
     * @param array $params Parameters for the rule (if any).
     * @return string The error message.
     */
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
        return $messages[$rule] ?? "The {$field} is invalid.";
    }

    // All methods below referenced in the validate method (@ Symbol Suppresses Errors)

    /**
     * Validates if the field is required (not empty).
     *
     * @param string $field The field name.
     * @return bool True if the field is not empty, otherwise false.
     */
    private function validateRequired($field) {
        return @isset($this->data[$field]) && !empty($this->data[$field]);
    }

    /**
     * Validates if the field is a valid email address.
     *
     * @param string $field The field name.
     * @return bool True if the field is a valid email address, otherwise false.
     */
    private function validateEmail($field) {
        return @filter_var($this->data[$field], FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validates if the field's value is greater than or equal to the minimum length.
     *
     * @param string $field The field name.
     * @param int $min The minimum length.
     * @return bool True if the field's value is greater than or equal to the minimum length, otherwise false.
     */
    private function validateMin($field, $min) {
        return @strlen($this->data[$field]) >= $min;
    }

    /**
     * Validates if the field's value is less than or equal to the maximum length.
     *
     * @param string $field The field name.
     * @param int $max The maximum length.
     * @return bool True if the field's value is less than or equal to the maximum length, otherwise false.
     */
    private function validateMax($field, $max) {
        return @strlen($this->data[$field]) <= $max;
    }

    /**
     * Validates if the field's value is a valid date in dd/mm/yyyy format.
     *
     * @param string $field The field name.
     * @return bool True if the field is a valid date, otherwise false.
     */
    private function validateDate($field) {
        $date = @DateTime::createFromFormat('d/m/Y', $this->data[$field]);
        return $date && $date->format('d/m/Y') === $this->data[$field];
    }

    /**
     * Validates if the field's value matches the given regex pattern.
     *
     * @param string $field The field name.
     * @param string $pattern The regular expression pattern.
     * @return bool True if the field matches the pattern, otherwise false.
     */
    private function validateRegex($field, $pattern) {
        return @preg_match($pattern, $this->data[$field]);
    }

    /**
     * Validates if the field's value is one of the predefined values.
     *
     * @param string $field The field name.
     * @param mixed ...$values The predefined values.
     * @return bool True if the field's value is one of the predefined values, otherwise false.
     */
    private function validateIn($field, ...$values) {
        return @in_array($this->data[$field], $values);
    }
}

?>
