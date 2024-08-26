<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\AlphaRule;
use Mousav1\Validify\Rules\BetweenRule;
use Mousav1\Validify\Rules\ConfirmedRule;
use Mousav1\Validify\Rules\EmailRule;
use Mousav1\Validify\Rules\InRule;
use Mousav1\Validify\Rules\MaxRule;
use Mousav1\Validify\Rules\MinRule;
use Mousav1\Validify\Rules\NumericRule;
use Mousav1\Validify\Rules\OptionalRule;
use Mousav1\Validify\Rules\RegexRule;
use Mousav1\Validify\Rules\RequiredRule;
use Mousav1\Validify\Rules\RequiredWithRule;
use Mousav1\Validify\Rules\Rule;
use Mousav1\Validify\Rules\UniqueRule;

class RuleMap {
    protected static $map = [
        'required' => RequiredRule::class,
        'max' => MaxRule::class,
        'required_with' => RequiredWithRule::class,
        'optional' => OptionalRule::class,
        'email' => EmailRule::class,
        'min' => MinRule::class,
        'numeric' => NumericRule::class,
        'confirmed' => ConfirmedRule::class,
        'unique' => UniqueRule::class,
        'in' => InRule::class,
        'between' => BetweenRule::class,
        'regex' => RegexRule::class,
        'alpha' => AlphaRule::class,
    ];

    public static function resolve(string $rule, array $options): Rule {

        $customRules = Validator::getCustomRules();
        if (isset($customRules[$rule])) {
            return call_user_func_array($customRules[$rule], $options);
        }

        $ruleClass = self::$map[$rule] ?? null;

        if ($ruleClass === null) {
            throw new \InvalidArgumentException("Rule {$rule} not found.");
        }

        return new $ruleClass(...$options);
    }
}