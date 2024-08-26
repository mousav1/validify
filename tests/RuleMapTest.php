<?php

use PHPUnit\Framework\TestCase;
use Mousav1\Validify\RuleMap;
use Mousav1\Validify\Rules\RequiredRule;
use Mousav1\Validify\Rules\EmailRule;
use Mousav1\Validify\Validator;

class RuleMapTest extends TestCase
{
    /**
     * Test resolving standard rules.
     */
    public function testResolveStandardRules()
    {
        $rule = RuleMap::resolve('required', []);
        $this->assertInstanceOf(RequiredRule::class, $rule);

        $rule = RuleMap::resolve('email', []);
        $this->assertInstanceOf(EmailRule::class, $rule);
    }

    /**
     * Test resolving custom rules.
     */
    public function testResolveCustomRules()
    {
        Validator::extend('custom_rule', function () {
            return new class extends \Mousav1\Validify\Rules\Rule {
                public function passes($field, $value, $data): bool
                {
                    return $value === 'valid';
                }

                public function message($field): string
                {
                    return "The {$field} must be 'valid'.";
                }
            };
        });

        $rule = RuleMap::resolve('custom_rule', []);
        $this->assertInstanceOf(\Mousav1\Validify\Rules\Rule::class, $rule);
    }

    /**
     * Test resolving a non-existent rule.
     */
    public function testResolveNonExistentRule()
    {
        $this->expectException(\InvalidArgumentException::class);
        RuleMap::resolve('non_existent_rule', []);
    }
}
