<?php

namespace Mousav1\Validify\Rules;

class EvenRule extends Rule
{
    public function passes(string $field, $value, array $data): bool
    {
        return is_numeric($value) && $value % 2 === 0;
    }

    public function message(string $field): string
    {
        return "{$field} must be an even number.";
    }
}
