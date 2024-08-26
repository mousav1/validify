<?php

namespace Mousav1\Validify\Rules;

class AlphaRule extends Rule
{
    public function name(): string
    {
        return 'alpha';
    }

    public function passes($field, $value, $data): bool
    {
        return is_string($value) && ctype_alpha($value);
    }

    public function message($field): string
    {
        return "{$field} field must contain only letters.";
    }
}
