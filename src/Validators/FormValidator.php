<?php
namespace WJM\Validators;

class FormValidator {
    public static function validate(array $fields, array $data): array {
        $errors = [];
        foreach ($fields as $field) {
            if (!empty($field['required']) && empty($data[$field['label']])) {
                $errors[] = "{$field['label']} is required.";
            }
        }
        return $errors;
    }
}
