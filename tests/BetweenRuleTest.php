<?php

namespace Mousav1\Validify\Tests\Rules;

use Mousav1\Validify\Rules\BetweenRule;
use PHPUnit\Framework\TestCase;

class BetweenRuleTest extends TestCase
{

    public function testBetweenRulePassesWhenLengthIsWithinRange()
    {
        $rule = new BetweenRule(3, 8);
        $this->assertTrue($rule->passes('username', 'valid', []), "Expected to pass when string length is within the range.");
    }

    public function testBetweenRuleFailsWhenLengthIsBelowMinimum()
    {
        $rule = new BetweenRule(3, 8);
        $this->assertFalse($rule->passes('username', 'hi', []), "Expected to fail when string length is below the minimum range.");
    }

    public function testBetweenRuleFailsWhenLengthIsAboveMaximum()
    {
        $rule = new BetweenRule(3, 8);
        $this->assertFalse($rule->passes('username', 'exceeding', []), "Expected to fail when string length is above the maximum range.");
    }

    public function testBetweenRulePassesWhenLengthIsExactlyMinimum()
    {
        $rule = new BetweenRule(3, 8);
        $this->assertTrue($rule->passes('username', 'min', []), "Expected to pass when string length is exactly the minimum range.");
    }

    public function testBetweenRulePassesWhenLengthIsExactlyMaximum()
    {
        $rule = new BetweenRule(3, 8);
        $this->assertTrue($rule->passes('username', 'maximum', []), "Expected to pass when string length is exactly the maximum range.");
    }

    public function testBetweenRuleErrorMessage()
    {
        $rule = new BetweenRule(3, 8);
        $expectedMessage = "username must be between 3 and 8.";
        $this->assertEquals($expectedMessage, $rule->message('username'), "Expected specific error message for between rule.");
    }
}
