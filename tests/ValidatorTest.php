<?php

use PHPUnit\Framework\TestCase;
use Mousav1\Validify\Validator;
use Mousav1\Validify\Rules\Rule;

class ValidatorTest extends TestCase
{
    /**
     * Test validating input data with rules.
     */
    public function testValidationSuccess()
    {
        $data = ['email' => 'test@example.com', 'age' => 25];
        $validator = new Validator($data);
        $validator->setRules([
            'email' => ['required', 'email'],
            'age' => ['required', 'numeric']
        ]);

        $this->assertTrue($validator->validate());
        $this->assertEmpty($validator->getErrors());
    }

    /**
     * Test validation with errors.
     */
    public function testValidationFailure()
    {
        $data = ['email' => 'invalid-email', 'age' => 'twenty-five'];
        $validator = new Validator($data);
        $validator->setRules([
            'email' => ['required', 'email'],
            'age' => ['required', 'numeric']
        ]);

        $this->assertFalse($validator->validate());
        $errors = $validator->getErrors();
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('age', $errors);
    }

    /**
     * Test custom validation rule addition.
     */
    public function testCustomValidationRule()
    {
        $data = ['username' => 'test_user'];
        Validator::extend('custom_rule', function () {
            return new class extends Rule {
                public function passes($field, $value, $data): bool
                {
                    return $value === 'test_user';
                }

                public function message($field): string
                {
                    return "The {$field} must be 'test_user'.";
                }
            };
        });

        $validator = new Validator($data);
        $validator->setRules([
            'username' => ['custom_rule']
        ]);

        $this->assertTrue($validator->validate());
        $this->assertEmpty($validator->getErrors());
    }

    /**
     * Test field aliases.
     */
    public function testFieldAliases()
    {
        $data = ['email_address' => 'test@example.com'];
        $validator = new Validator($data);
        $validator->setAliases([
            'email_address' => 'Email'
        ]);

        $validator->setRules([
            'email_address' => ['required', 'email']
        ]);

        $this->assertTrue($validator->validate());
        $this->assertEmpty($validator->getErrors());
    }
}
