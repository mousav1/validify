<?php

namespace Mousav1\Validify\Rules;

class MinRule extends Rule
{
    protected int $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    public function name(): string
    {
        return 'min';
    }

    public function passes($field, $value, $data): bool
    {
        return strlen($value) >= $this->min;
    }

    public function message($field): string
    {
        return "{$field} must be at least {$this->min} characters.";
    }
}