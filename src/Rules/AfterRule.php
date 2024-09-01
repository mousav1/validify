<?php

namespace Mousav1\Validify\Rules;

use DateTime;
use InvalidArgumentException;

class AfterRule extends Rule
{
    protected string $date;

    /**
     * Constructor.
     *
     * @param string $date The date to compare against.
     */
    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function name(): string
    {
        return 'after';
    }

    public function passes($field, $value, $data): bool
    {
        $dateValue = new DateTime($value);
        $comparisonDate = new DateTime($this->date);

        return $dateValue > $comparisonDate;
    }

    public function message($field): string
    {
        return "{$field} must be a date after {$this->date}.";
    }
}
