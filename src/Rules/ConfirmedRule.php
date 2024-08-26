<?php

namespace Mousav1\Validify\Rules;

class ConfirmedRule extends Rule
{
    public function passes($field, $value, $data): bool
    {
        $confirmationField = "{$field}_confirmation";
        return isset($data[$confirmationField]) && $value === $data[$confirmationField];
    }

    public function message($field): string
    {
        return "{$field} confirmation does not match.";
    }
}
