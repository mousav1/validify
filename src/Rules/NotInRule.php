<?php

namespace Mousav1\Validify\Rules;

use Mousav1\Validify\Validator;

class NotInRule extends Rule
{
    /**
     * @var array $values List of values that the field value should not be in.
     */
    protected array $values;

    /**
     * Constructor for NotInRule.
     *
     * @param array $values List of values.
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns the name of the rule.
     *
     * @return string
     */
    public function name(): string
    {
        return 'not_in';
    }

    /**
     * Validates that the given field value is not in the specified list of values.
     *
     * @param string $field The name of the field being validated.
     * @param mixed $value The value of the field.
     * @param array $data The entire data set being validated.
     * @return bool
     */
    public function passes($field, $value, $data, bool $strict = false): bool
    {
        return !in_array($value, $this->values, $strict);
    }

    /**
     * Returns the error message if validation fails.
     *
     * @param string $field The name of the field.
     * @return string
     */
    public function message($field): string
    {
        $values = implode(', ', $this->values);
        return "{$field} must not be in the list: {$values}.";
    }
}
