<?php

namespace Mousav1\Validify\Rules;

class RegexRule extends Rule
{

    public function __construct(protected string $pattern)
    {
    }

    public function name(): string
    {
        return 'regex';
    }

    public function passes($field, $value, $data): bool
    {
        return preg_match($this->pattern, $value);
    }

    public function message($field): string
    {
        return "The {$field} format is invalid.";
    }
}
