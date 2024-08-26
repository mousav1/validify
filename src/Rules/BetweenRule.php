<?php

namespace Mousav1\Validify\Rules;

class BetweenRule extends Rule
{
    protected $min;
    protected $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function name(): string
    {
        return 'between';
    }

    public function passes($field, $value, $data): bool
    {
        return $value >= $this->min && $value <= $this->max;
    }

    public function message($field): string
    {
        return "{$field} must be between {$this->min} and {$this->max}.";
    }
}
