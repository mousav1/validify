<?php

namespace Mousav1\Validify\Errors;

/**
 * Class ValidationErrorCollection
 *
 * Manages a collection of validation errors. Provides methods to add errors,
 * check if there are any errors, and retrieve all errors.
 *
 * @package Mousav1\Validify\Errors
 */
class ValidationErrorCollection
{
    /**
     * @var array<string, array<string>> $errors
     * Array to hold the validation errors. The key is the field name and the value
     * is an array of error messages related to that field.
     */
    protected array $errors = [];

    /**
     * Adds a validation error to the collection.
     *
     * @param string $key The field name or identifier for the error.
     * @param string $value The error message to be added.
     *
     * @return void
     */
    public function add(string $key, string $value): void
    {
        $this->errors[$key][] = $value;
    }

    /**
     * Retrieves all validation errors.
     *
     * @return array<string, array<string>> An associative array where the keys are field names
     *                                      and the values are arrays of error messages.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Checks if there are any validation errors in the collection.
     *
     * @return bool True if there are errors, false otherwise.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
