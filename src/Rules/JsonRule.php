<?php

namespace Mousav1\Validify\Rules;

class JsonRule extends Rule
{
    public function name(): string
    {
        return 'json';
    }

    public function passes($field, $value, $data): bool
    {
        if (!is_string($value)) {
            return false;
        }
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function message($field): string
    {
        return "{$field} must be a valid JSON string.";
    }
}
