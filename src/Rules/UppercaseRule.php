<?php

namespace Mousav1\Validify\Rules;

class UppercaseRule extends Rule
{
    public function name(): string
    {
        return 'uppercase';
    }

    public function passes($field, $value, $data): bool
    {
        return is_string($value) && strtoupper($value) === $value;
    }

    public function message($field): string
    {
        return "{$field} must be uppercase.";
    }
}
