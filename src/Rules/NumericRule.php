<?php

namespace Mousav1\Validify\Rules;

class NumericRule extends Rule
{
    public function passes($field, $value, $data): bool
    {
        return is_numeric($value);
    }

    public function message($field): string
    {
        return "{$field} must be a numeric value.";
    }
}
