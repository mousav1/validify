<?php

namespace Mousav1\Validify\Rules;

abstract class Rule
{
    abstract public function passes(string $field, $value, array $data): bool;

    abstract public function message(string $field): string;
}