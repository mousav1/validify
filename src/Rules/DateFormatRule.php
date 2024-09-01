<?php

namespace Mousav1\Validify\Rules;

use DateTime;
use InvalidArgumentException;

class DateFormatRule extends Rule
{
    protected string $format;

    /**
     * Constructor.
     *
     * @param string $format The date format to validate against.
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function name(): string
    {
        return 'date_format';
    }

    public function passes($field, $value, $data): bool
    {
        $dateTime = DateTime::createFromFormat($this->format, $value);
        return $dateTime && $dateTime->format($this->format) === $value;
    }

    public function message($field): string
    {
        return "{$field} must be a valid date format: {$this->format}.";
    }
}
