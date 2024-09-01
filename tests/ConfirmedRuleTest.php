<?php

use Mousav1\Validify\Rules\ConfirmedRule;
use PHPUnit\Framework\TestCase;

class ConfirmedRuleTest extends TestCase
{
    public function testConfirmedRulePassesWhenValuesMatch()
    {
        $rule = new ConfirmedRule();
        $data = [
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];
        
        $this->assertTrue($rule->passes('password', 'secret', $data), "Expected to pass when values match.");
    }

    public function testConfirmedRuleFailsWhenValuesDoNotMatch()
    {
        $rule = new ConfirmedRule();
        $data = [
            'password' => 'secret',
            'password_confirmation' => 'notsecret'
        ];
        
        $this->assertFalse($rule->passes('password', 'secret', $data), "Expected to fail when values do not match.");
    }

    public function testConfirmedRuleFailsWhenConfirmationFieldIsMissing()
    {
        $rule = new ConfirmedRule();
        $data = [
            'password' => 'secret',
        ];
        
        $this->assertFalse($rule->passes('password', 'secret', $data), "Expected to fail when confirmation field is missing.");
    }

    public function testConfirmedRuleErrorMessage()
    {
        $rule = new ConfirmedRule();
        $expectedMessage = "password confirmation does not match.";
        $this->assertEquals($expectedMessage, $rule->message('password'), "Expected specific error message for confirmed rule.");
    }
}
