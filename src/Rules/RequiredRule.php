<?php

namespace Mousav1\Validify\Rules;

class RequiredRule extends Rule
{
    public function passes($field, $value, $data): bool
    {
        return !empty($value);
    }

    public function name(): string
    {
        return 'required';
    }

    public function message($field): string
    {
        return "{$field} is required";
    }
}