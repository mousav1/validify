<?php

namespace Mousav1\Validify\Rules;

class LowercaseRule extends Rule
{
    public function name(): string
    {
        return 'lowercase';
    }

    public function passes($field, $value, $data): bool
    {
        return is_string($value) && strtolower($value) === $value;
    }

    public function message($field): string
    {
        return "{$field} must be lowercase.";
    }
}
