<?php

namespace Mousav1\Validify\Rules;

class IntegerRule extends Rule
{
    /**
     * Returns the name of the rule.
     *
     * @return string
     */
    public function name(): string
    {
        return 'integer';
    }

    /**
     * Validates that the given field value is an integer.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field.
     * @param array $data The entire data set being validated.
     * @return bool
     */
    public function passes($field, $value, $data): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Returns the error message if validation fails.
     *
     * @param string $field The name of the field.
     * @return string
     */
    public function message($field): string
    {
        return "{$field} must be an integer.";
    }
}
