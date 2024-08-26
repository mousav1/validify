<?php

namespace Mousav1\Validify\Rules;

class MaxRule extends Rule
{
    protected int $max;
    
    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function passes(string $field, $value, array $data): bool
    {
        return is_string($value) && strlen($value) <= $this->max;
    }

    public function message(string $field): string
    {
        return "{$field} must be a max of {$this->max} characters";
    }
}