<?php

use Mousav1\Validify\Rules\AlphaRule;
use PHPUnit\Framework\TestCase;

class AlphaRuleTest extends TestCase
{
    public function testAlphaRulePassesWithOnlyLetters()
    {
        $rule = new AlphaRule();
        $this->assertTrue($rule->passes('name', 'Hello', []), "Expected to pass when the value contains only letters.");
    }

    public function testAlphaRuleFailsWithNonAlphaCharacters()
    {
        $rule = new AlphaRule();
        $this->assertFalse($rule->passes('name', 'Hello123', []), "Expected to fail when the value contains numbers.");
        $this->assertFalse($rule->passes('name', 'Hello@', []), "Expected to fail when the value contains special characters.");
    }

    public function testAlphaRuleFailsWithEmptyString()
    {
        $rule = new AlphaRule();
        $this->assertFalse($rule->passes('name', '', []), "Expected to fail when the value is an empty string.");
    }

    public function testAlphaRuleErrorMessage()
    {
        $rule = new AlphaRule();
        $this->assertEquals('name field must contain only letters.', $rule->message('name'), "Expected specific error message for alpha rule.");
    }
}
