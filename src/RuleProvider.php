<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\Rule;

class RuleProvider
{
    /**
     * @var array A map of rule names to their corresponding classes.
     */
    protected static array $map = [];

    /**
     * @var array A cache of resolved rule instances.
     */
    protected static array $cache = [];

    /**
     * Registers a rule class with a given name.
     *
     * @param string $name The name of the rule.
     * @param string $ruleClass The class name of the rule.
     * @throws \InvalidArgumentException if the rule class does not extend Rule.
     */
    public static function register(string $name, string $ruleClass): void
    {
        if (!is_subclass_of($ruleClass, Rule::class)) {
            throw new \InvalidArgumentException("{$ruleClass} must extend Rule.");
        }
        self::$map[$name] = $ruleClass;
    }

    /**
     * Resolves a rule instance by its name and options.
     *
     * @param string $rule The name of the rule.
     * @param array $options The options to pass to the rule's constructor.
     * @return Rule The resolved rule instance.
     * @throws \InvalidArgumentException if the rule is not found in registered rules.
     */
    public static function resolve(string $rule, array $options): Rule
    {
        $cacheKey = md5($rule . serialize($options));
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $customRules = Validator::getCustomRules();
        if (isset($customRules[$rule])) {
            $ruleInstance = call_user_func_array($customRules[$rule], $options);
        } else {
            if (!isset(self::$map[$rule])) {
                throw new \InvalidArgumentException("Rule '{$rule}' not found in registered rules.");
            }
            $ruleInstance = new self::$map[$rule](...$options);
        }

        self::$cache[$cacheKey] = $ruleInstance;
        return $ruleInstance;
    }
}
