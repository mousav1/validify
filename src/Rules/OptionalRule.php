<?php

namespace Mousav1\Validify\Rules;

class OptionalRule extends Rule
{
    public function passes($field, $value, $data): bool
    {
        return true;
    }

    public function name(): string
    {
        return 'optional';
    }

    public function message($field): string
    {
        return '';
    }
}