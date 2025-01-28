<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Errors\ValidationErrorCollection;
use Mousav1\Validify\Rules\{
    AfterRule,
    AlphaRule,
    ArrayRule,
    BeforeRule,
    BetweenRule,
    BooleanRule,
    ConfirmedRule,
    DateFormatRule,
    EmailRule,
    InRule,
    IntegerRule,
    IsUrlRule,
    JsonRule,
    LowercaseRule,
    MaxRule,
    MinRule,
    NotInRule,
    NumericRule,
    OptionalRule,
    RegexRule,
    RequiredRule,
    RequiredWithRule,
    Rule,
    UppercaseRule
};

/**
 * Class Validator
 *
 * @package Mousav1\Validify
 */
class Validator
{
    /**
     * @var array
     */
    protected array $conditionalRules = [];

    /**
     * @var array
     */
    protected array $inputData;

    /**
     * @var array
     */
    protected array $validationRules = [];

    /**
     * @var ValidationErrorCollection
     */
    protected ValidationErrorCollection $validationErrorCollection;

    /**
     * @var array
     */
    protected static array $fieldAliases = [];

    /**
     * @var array
     */
    protected static array $customValidationRules = [];

    /**
     * @var array
     */
    protected array $preValidationCallbacks = [];

    /**
     * @var array
     */
    protected array $customMessages = [];

    /**
     * @var RuleProvider
     */
    protected RuleProvider $ruleProvider;

    /**
     * Validator constructor.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param RuleProvider|null $ruleProvider
     * @param ValidationErrorCollection|null $validationErrorCollection
     */
    public function __construct(
        array $data,
        array $rules = [],
        array $messages = [],
        RuleProvider $ruleProvider = null,
        ValidationErrorCollection $validationErrorCollection = null
    ) {
        $this->inputData = $this->extractWildcardData($data);
        $this->validationErrorCollection = $validationErrorCollection ?? new ValidationErrorCollection();
        $this->ruleProvider = $ruleProvider ?? new RuleProvider();
        $this->validationRules = $rules;
        $this->customMessages = $messages;
        $this->initializeDefaultRules();
    }

    /**
     * Get the rule provider.
     *
     * @return RuleProvider
     */
    public function getRuleProvider(): RuleProvider
    {
        return $this->ruleProvider;
    }

    /**
     * Add a conditional rule.
     *
     * @param string $field
     * @param array $rules
     * @param callable $condition
     */
    public function addConditionalRule(string $field, array $rules, callable $condition): void
    {
        $this->conditionalRules[] = new ConditionalRule($field, $rules, $condition);
    }

    /**
     * Initialize default rules.
     */
    protected function initializeDefaultRules(): void
    {
        $defaultRules = [
            'required' => RequiredRule::class,
            'email' => EmailRule::class,
            'between' => BetweenRule::class,
            'confirmed' => ConfirmedRule::class,
            'max' => MaxRule::class,
            'required_with' => RequiredWithRule::class,
            'url' => IsUrlRule::class,
            'alpha' => AlphaRule::class,
            'regex' => RegexRule::class,
            'in' => InRule::class,
            'numeric' => NumericRule::class,
            'min' => MinRule::class,
            'optional' => OptionalRule::class,
            'array' => ArrayRule::class,
            'integer' => IntegerRule::class,
            'boolean' => BooleanRule::class,
            'not_in' => NotInRule::class,
            'uppercase' => UppercaseRule::class,
            'lowercase' => LowercaseRule::class,
            'json' => JsonRule::class,
            'date_format' => DateFormatRule::class,
            'after' => AfterRule::class,
            'before' => BeforeRule::class,
        ];

        foreach ($defaultRules as $name => $class) {
            $this->ruleProvider::register($name, $class);
        }
    }

    /**
     * Set custom messages.
     *
     * @param array $messages
     */
    public function setCustomMessages(array $messages): void
    {
        $this->customMessages = $messages;
    }

    /**
     * Get a field instance.
     *
     * @param string $field
     * @return Field
     */
    public function field(string $field): Field
    {
        return new Field($this, $field);
    }

    /**
     * Extend the validator with a custom rule.
     *
     * @param string $ruleName
     * @param callable $callback
     */
    public static function extend(string $ruleName, callable $callback): void
    {
        self::$customValidationRules[$ruleName] = $callback;
    }

    /**
     * Add a callback to be executed before validation.
     *
     * @param callable $callback
     */
    public function beforeValidate(callable $callback): void
    {
        $this->preValidationCallbacks[] = $callback;
    }

    /**
     * Get custom validation rules.
     *
     * @return array
     */
    public static function getCustomRules(): array
    {
        return self::$customValidationRules;
    }

    /**
     * Extract wildcard data from an array.
     *
     * @param array $array
     * @param string $root
     * @param array $results
     * @return array
     */
    protected function extractWildcardData(array $array, string $root = '', array $results = []): array
    {
        foreach ($array as $key => $value) {
            $newKey = $root . $key;
            if (is_array($value)) {
                $results = $this->extractWildcardData($value, $newKey . '.', $results);
            } else {
                $results[$newKey] = $value;
            }
        }
        return $results;
    }

    /**
     * Set validation rules.
     *
     * @param array $rules
     */
    public function setRules(array $rules): void
    {
        $this->validationRules = $rules;
    }

    /**
     * Set field aliases.
     *
     * @param array $aliases
     */
    public function setAliases(array $aliases): void
    {
        self::$fieldAliases = $aliases;
    }

    /**
     * Validate the input data.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $this->executePreValidationCallbacks();
        $this->applyConditionalRules();

        foreach ($this->validationRules as $field => $rules) {
            $resolvedRules = $this->resolveRules(is_array($rules) ? $rules : explode('|', $rules));
            $this->validateField($field, $resolvedRules);
        }

        return !$this->validationErrorCollection->hasErrors();
    }

    /**
     * Execute pre-validation callbacks.
     */
    protected function executePreValidationCallbacks(): void
    {
        foreach ($this->preValidationCallbacks as $callback) {
            call_user_func_array($callback, [&$this->inputData]);
        }
    }

    /**
     * Apply conditional rules.
     */
    protected function applyConditionalRules(): void
    {
        foreach ($this->conditionalRules as $conditionalRule) {
            if ($rules = $conditionalRule->apply($this->inputData)) {
                foreach ($rules as $field => $fieldRules) {
                    $this->validationRules[$field] = array_merge($this->validationRules[$field] ?? [], $fieldRules);
                }
            }
        }
    }

    /**
     * Validate a field using the provided rules.
     *
     * @param string $field
     * @param array $rules
     */
    protected function validateField(string $field, array $rules): void
    {
        $optional = $this->resolveRulesContainsOptional($rules);
        foreach ($this->getMatchingData($field) as $matchedField) {
            $value = $this->getFieldValue($matchedField);
            if ($optional && $value === '') continue;
            $this->validateUsingRuleObject($matchedField, $value, $rules);
        }
    }

    /**
     * Check if the rules contain an optional rule.
     *
     * @param array $rules
     * @return bool
     */
    protected function resolveRulesContainsOptional(array $rules): bool
    {
        return array_reduce($rules, fn($carry, $rule) => $carry || $rule instanceof OptionalRule, false);
    }

    /**
     * Resolve rules from strings to rule objects.
     *
     * @param array $rules
     * @return array
     */
    protected function resolveRules(array $rules): array
    {
        return array_map(fn($rule) => is_string($rule) ? $this->getRuleFromString($rule) : $rule, $rules);
    }

    /**
     * Get a rule object from a string.
     *
     * @param string $rule
     * @return Rule
     */
    protected function getRuleFromString(string $rule): Rule
    {
        [$name, $params] = explode(':', $rule) + [null, ''];
        $options = $params !== null ? explode(',', $params) : [];

        if (isset(self::$customValidationRules[$name])) {
            return call_user_func_array(self::$customValidationRules[$name], $options);
        }

        return $this->ruleProvider->resolve($name, $options);
    }

    /**
     * Validate a field using a rule object.
     *
     * @param string $field
     * @param mixed $value
     * @param array $rules
     */
    protected function validateUsingRuleObject(string $field, mixed $value, array $rules): void
    {
        foreach ($rules as $rule) {
            if (!$rule->passes($field, $value, $this->inputData)) {
                $this->addValidationError($field, $rule);
            }
        }
    }

    /**
     * Add a validation error.
     *
     * @param string $field
     * @param Rule $rule
     */
    protected function addValidationError(string $field, Rule $rule): void
    {
        $ruleName = $rule->name();
        $messageKey = "{$field}.{$ruleName}";
        $message = $this->customMessages[$field][$ruleName]
            ?? $this->customMessages[$messageKey]
            ?? $rule->message(self::alias($field));
        $this->validationErrorCollection->add($field, $message);
    }

    /**
     * Get matching data for a field.
     *
     * @param string $field
     * @return array
     */
    protected function getMatchingData(string $field): array
    {
        $pattern = '/^' . str_replace('*', '([^\.]+)', $field) . '$/';
        return preg_grep($pattern, array_keys($this->inputData));
    }

    /**
     * Get the value of a field.
     *
     * @param string $field
     * @return mixed
     */
    public function getFieldValue(string $field): mixed
    {
        return $this->inputData[$field] ?? null;
    }

    /**
     * Get aliases for fields.
     *
     * @param array $fields
     * @return array
     */
    public static function aliases(array $fields): array
    {
        return array_map(fn($field) => self::alias($field), $fields);
    }

    /**
     * Get the alias for a field.
     *
     * @param string $field
     * @return string
     */
    public static function alias(string $field): string
    {
        return self::$fieldAliases[$field] ?? $field;
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->validationErrorCollection->getErrors();
    }
}
