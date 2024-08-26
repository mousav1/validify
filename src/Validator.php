<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Errors\ValidationErrorCollection;
use Mousav1\Validify\Rules\OptionalRule;
use Mousav1\Validify\Rules\Rule;

class Validator
{

    /**
     * @var array $inputData The data to be validated.
     */
    protected array $inputData;

    /**
     * @var array $validationRules The validation rules for each field.
     */
    protected array $validationRules = [];

    /**
     * @var ValidationErrorCollection ValidationErrorCollection Holds validation errors.
     */
    protected ValidationErrorCollection $validationErrorCollection;

    /**
     * @var array $fieldAliases Aliases for field names.
     */
    protected static array $fieldAliases = [];

    /**
     * @var array $customValidationRules Custom validation rules defined by the user.
     */
    protected static array $customValidationRules = [];

    /**
     * @var array $preValidationCallbacks Callbacks to be executed before validation.
     */
    protected array $preValidationCallbacks = [];

    /**
     * Constructor method.
     *
     * @param array $data The input data to be validated.
     */
    public function __construct(array $data)
    {
        // Extracts wildcard data from the input and stores it
        $this->inputData = $this->extractWildcardData($data);

        // Initializes an ValidationErrorCollection instance to store validation errors
        $this->validationErrorCollection = new ValidationErrorCollection();
    }

    /**
     * Adds a custom validation rule.
     *
     * @param string $ruleName The name of the custom rule.
     * @param callable $callback The function that defines the custom rule.
     */
    public static function extend(string $ruleName, callable $callback): void
    {
        self::$customValidationRules[$ruleName] = $callback;
    }

    /**
     * Adds a callback to be executed before validation.
     *
     * @param callable $callback The callback function.
     */
    public function beforeValidate(callable $callback): void
    {
        $this->preValidationCallbacks[] = $callback;
    }

    /**
     * Retrieves all custom validation rules.
     *
     * @return array The array of custom validation rules.
     */
    public static function getCustomRules(): array
    {
        return self::$customValidationRules;
    }

    /**
     * Recursively extracts data from nested arrays, converting it to a dot notation format.
     *
     * @param array $array The array to extract data from.
     * @param string $root The root key for nested data (used internally).
     * @param array $results The accumulated results (used internally).
     * @return array The flattened array with dot notation keys.
     */
    protected function extractWildcardData(array $array, string $root = '', array $results = []): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // Recursively merge nested arrays with the root key as prefix
                $results = array_merge($results, $this->extractWildcardData($value, $root . $key . '.'));
            } else {
                $results[$root . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Sets the validation rules.
     *
     * @param array $rules The validation rules to be applied.
     */
    public function setRules(array $rules): void
    {
        $this->validationRules = $rules;
    }

    /**
     * Sets the field aliases.
     *
     * @param array $aliases The array of field aliases.
     */
    public function setAliases(array $aliases): void
    {
        self::$fieldAliases = $aliases;
    }

    /**
     * Validates the input data against the validation rules.
     *
     * @return bool True if validation passes, false if there are errors.
     */
    public function validate(): bool
    {
        // Execute pre-validation callbacks
        foreach ($this->preValidationCallbacks as $callback) {
            call_user_func_array($callback, [&$this->inputData]);
        }

        // Validate each field against its associated rules
        foreach ($this->validationRules as $field => $rules) {
            $resolvedRules = $this->resolveRules($rules);
            foreach ($resolvedRules as $rule) {
                $this->validateRule($field, $rule, $this->resolveRulesContainsOptional($resolvedRules));
            }
        }

        // Return true if there are no errors
        return !$this->validationErrorCollection->hasErrors();
    }

    // protected function resolveRulesContainsOptional(array $rules) {
    //     foreach($rules as $rule) {
    //         if($rule instanceof OptionalRule) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    /**
     * Checks if the resolved rules contain an OptionalRule.
     *
     * @param array $rules The array of resolved rules.
     * @return bool True if OptionalRule is found, otherwise false.
     */
    protected function resolveRulesContainsOptional(array $rules): bool
    {
        return array_reduce($rules, fn($carry, $rule) => $carry || $rule instanceof OptionalRule, false);
    }

    /**
     * Resolves rules from strings to Rule objects.
     *
     * @param array $rules The array of rules as strings or objects.
     * @return array The array of resolved Rule objects.
     */
    protected function resolveRules(array $rules): array
    {
        return array_map(fn($rule) => is_string($rule) ? $this->getRuleFromString($rule) : $rule, $rules);
    }

    // protected function getRuleFromString($rule) {
    //     return $this->newRuleFromMap(
    //         ($exploded = explode(':', $rule))[0],
    //         explode(',', end($exploded))
    //     );
    // }


    /**
     * Converts a rule string to a Rule object.
     *
     * @param string $rule The rule as a string.
     * @return Rule The corresponding Rule object.
     */
    protected function getRuleFromString(string $rule): Rule
    {
        [$name, $params] = explode(':', $rule) + [null, ''];
        $options = $params !== null ? explode(',', $params) : [];

        // Check if the rule is a custom rule
        if (isset(self::$customValidationRules[$name])) {
            return call_user_func_array(self::$customValidationRules[$name], $options);
        }

        // If not, resolve it from the rule map
        return $this->newRuleFromMap($name, $options);
    }

    /**
     * Creates a new Rule object using the rule map.
     *
     * @param string $rule The rule name.
     * @param array $options The options for the rule.
     * @return Rule The created Rule object.
     */
    protected function newRuleFromMap(string $rule, array $options): Rule
    {
        return RuleMap::resolve($rule, $options);
    }

    /**
     * Validates a single field using the given Rule object.
     *
     * @param string $field The field name.
     * @param Rule $rule The Rule object.
     * @param bool $optional Whether the field is optional.
     */
    protected function validateRule(string $field, Rule $rule, bool $optional = false): void
    {
        foreach ($this->getMatchingData($field) as $matchedField) {
            $value = $this->getFieldValue($matchedField);
            if ($optional && $value === '') continue;
            $this->validateUsingRuleObject($matchedField, $value, $rule);
        }
    }

    /**
     * Validates a field value using the Rule object.
     *
     * @param string $field The field name.
     * @param mixed $value The value to be validated.
     * @param Rule $rule The Rule object.
     */
    protected function validateUsingRuleObject(string $field, mixed $value, Rule $rule): void
    {
        if (!$rule->passes($field, $value, $this->inputData)) {
            $this->validationErrorCollection->add($field, $rule->message(self::alias($field)));
        }
    }

    /**
     * Retrieves fields that match a wildcard pattern.
     *
     * @param string $field The field name or pattern.
     * @return array The array of matching field names.
     */
    protected function getMatchingData(string $field): array
    {
        $pattern = '/^' . str_replace('*', '([^\.]+)', $field) . '$/';
        return preg_grep($pattern, array_keys($this->inputData));
    }

    /**
     * Retrieves the value of a field.
     *
     * @param string $field The field name.
     * @return mixed The value of the field, or null if not found.
     */
    public function getFieldValue(string $field): mixed
    {
        return $this->inputData[$field] ?? null;
    }

    /**
     * Converts an array of field names to their aliases.
     *
     * @param array $fields The array of field names.
     * @return array The array of field aliases.
     */
    public static function aliases(array $fields): array
    {
        return array_map(function($field) {
            return self::alias($field);
        }, $fields);
    }

    /**
     * Retrieves the alias for a field.
     *
     * @param string $field The field name.
     * @return string The alias, or the original field name if no alias is set.
     */
    public static function alias(string $field): string
    {
        return self::$fieldAliases[$field] ?? $field;
    }

    /**
     * Retrieves all validation errors.
     *
     * @return array The array of validation errors.
     */
    public function getErrors(): array
    {
        return $this->validationErrorCollection->getErrors();
    }
}