<?php

namespace Mousav1\Validify\Rules;

class InRule extends Rule
{
    protected array $allowedValues;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function passes($field, $value, $data): bool
    {
        return in_array($value, $this->allowedValues);
    }

    public function message($field): string
    {
        $values = implode(', ', $this->allowedValues);
        return "{$field} must be one of the following: {$values}.";
    }
}
