<?php

namespace Mousav1\Validify\Rules;

class IsUrlRule extends Rule
{
    public function passes($field, $value, $data): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public function name(): string
    {
        return 'url';
    }

    public function message($field): string
    {
        return "{$field} must be a valid URL.";
    }
}
