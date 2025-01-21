<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\Rule;

class RuleProvider
{
    protected static array $map = [];

    public static function register(string $name, string $ruleClass): void
    {
        if (!is_subclass_of($ruleClass, Rule::class)) {
            throw new \InvalidArgumentException("{$ruleClass} must extend Rule.");
        }

        self::$map[$name] = $ruleClass;
    }

    public static function resolve(string $rule, array $options): Rule
    {

        $customRules = Validator::getCustomRules();
        if (isset($customRules[$rule])) {
            return call_user_func_array($customRules[$rule], $options);
        }

        $ruleClass = self::$map[$rule] ?? null;

        if (!$ruleClass) {
            throw new \InvalidArgumentException("Rule '{$rule}' not found in registered rules.");
        }

        return new $ruleClass(...$options);
    }
}
