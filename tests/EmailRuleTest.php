<?php

namespace Mousav1\Validify\Tests\Rules;

use Mousav1\Validify\Rules\EmailRule;
use PHPUnit\Framework\TestCase;

class EmailRuleTest extends TestCase
{
    public function testEmailRulePassesForValidEmail()
    {
        $rule = new EmailRule();
        $data = ['email' => 'test@example.com'];
        
        $this->assertTrue($rule->passes('email', 'test@example.com', $data), "Expected to pass for a valid email address.");
    }

    public function testEmailRuleFailsForInvalidEmail()
    {
        $rule = new EmailRule();
        $data = ['email' => 'invalid-email'];
        
        $this->assertFalse($rule->passes('email', 'invalid-email', $data), "Expected to fail for an invalid email address.");
    }

    public function testEmailRuleFailsForEmptyString()
    {
        $rule = new EmailRule();
        $data = ['email' => ''];
        
        $this->assertFalse($rule->passes('email', '', $data), "Expected to fail for an empty string.");
    }

    public function testEmailRuleFailsForEmailWithInvalidCharacters()
    {
        $rule = new EmailRule();
        $data = ['email' => 'invalid@exa$mple.com'];
        
        $this->assertFalse($rule->passes('email', 'invalid@exa$mple.com', $data), "Expected to fail for email with invalid characters.");
    }

    public function testEmailRuleErrorMessage()
    {
        $rule = new EmailRule();
        $expectedMessage = "email must be valid email address";
        $this->assertEquals($expectedMessage, $rule->message('email'), "Expected specific error message for email rule.");
    }
}
