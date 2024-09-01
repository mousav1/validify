<?php

namespace Mousav1\Validify\Rules;

class BooleanRule extends Rule
{
    /**
     * Returns the name of the rule.
     *
     * @return string
     */
    public function name(): string
    {
        return 'boolean';
    }

    /**
     * Validates that the given field value is a boolean.
     * Accepted values: true, false, 1, 0, "1", "0".
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field.
     * @param array $data The entire data set being validated.
     * @return bool
     */
    public function passes($field, $value, $data): bool
    {
        return in_array($value, [true, false, 1, 0, "1", "0"], true);
    }

    /**
     * Returns the error message if validation fails.
     *
     * @param string $field The name of the field.
     * @return string
     */
    public function message($field): string
    {
        return "{$field} must be a boolean value (true, false, 1, 0, '1', '0').";
    }
}
