<?php

namespace Mousav1\Validify\Rules;

use DateTime;
use InvalidArgumentException;

class BeforeRule extends Rule
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
        return 'before';
    }

    public function passes($field, $value, $data): bool
    {
        $dateValue = new DateTime($value);
        // Check if the comparison is with a date or a field
        if (isset($data[$this->date])) {
            $comparisonDate = new DateTime($data[$this->date]);
        } else {
            // If it's not a field, treat it as a direct date comparison
            $comparisonDate = new DateTime($this->date);
        }

        return $dateValue < $comparisonDate;
    }

    public function message($field): string
    {
        return "{$field} must be a date before {$this->date}.";
    }
}
