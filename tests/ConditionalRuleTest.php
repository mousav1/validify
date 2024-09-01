<?php

use PHPUnit\Framework\TestCase;
use Mousav1\Validify\Validator;

class ConditionalRuleTest extends TestCase
{
    /**
     * Test that the conditional rule is applied when the condition is met.
     */
    public function testRuleAppliedWhenConditionIsTrue()
    {
        $data = [
            'age' => 25,
            'license' => ''
        ];

        $validator = new Validator($data);

        // Define a condition that returns true
        $condition = function ($data) {
            return $data['age'] > 18;
        };

        // Add a conditional rule
        $validator->addConditionalRule('license', ['required'], $condition);

        // Perform validation
        $validator->validate();

        // Check if the 'required' rule was applied
        $errors = $validator->getErrors();
        $this->assertArrayHasKey('license', $errors, 'The field "license" should have validation errors if the condition is true.');
    }

    /**
     * Test that the conditional rule is not applied when the condition is not met.
     */
    public function testRuleNotAppliedWhenConditionIsFalse()
    {
        $data = [
            'age' => 16,
            'license' => ''
        ];

        $validator = new Validator($data);

        // Define a condition that returns false
        $condition = function ($data) {
            return $data['age'] > 18;
        };

        // Add a conditional rule
        $validator->addConditionalRule('license', ['required'], $condition);

        // Perform validation
        $validator->validate();

        // Check if there are no errors for the 'license' field
        $errors = $validator->getErrors();
        $this->assertArrayNotHasKey('license', $errors, 'The field "license" should not have validation errors if the condition is false.');
    }
}
