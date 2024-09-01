<?php

namespace Tests;

use Mousav1\Validify\Validator;
use PHPUnit\Framework\TestCase;

class DateAndTimeValidationTest extends TestCase
{
    public function testDateFormatRule()
    {
        $data = ['date_of_birth' => '2024-09-01'];
        $rules = ['date_of_birth' => 'date_format:Y-m-d']; // Ensure this is an array

        $validator = new Validator($data, $rules);
        $this->assertTrue($validator->validate());
    }

    public function testAfterRule()
    {
        $data = ['event_date' => '2024-09-02'];
        $rules = ['event_date' => 'after:2024-09-01']; // Ensure this is an array

        $validator = new Validator($data, $rules);
        $this->assertTrue($validator->validate());
    }

    public function testBeforeRule()
    {
        $data = ['event_date' => '2024-08-31'];
        $rules = ['event_date' => 'before:2024-09-01']; // Ensure this is an array

        $validator = new Validator($data, $rules);
        $this->assertTrue($validator->validate());
    }
}
