<?php

namespace Mousav1\Validify\Rules;

class EmailRule extends Rule
{
    public function name(): string
    {
        return 'email';
    }
    
    public function passes($field, $value, $data): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function message($field): string
    {
        return "{$field} must be valid email address";
    }
}