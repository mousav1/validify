<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\Rule;

class ConditionalRule
{
    protected string $field;
    protected array $rules;
    protected $condition;

    /**
     * Constructor to create a conditional rule.
     *
     * @param string $field The field to apply the rules to
     * @param array $rules The rules to apply if the condition is met
     * @param callable $condition A callback function that determines if the rules should be applied
     */
    public function __construct(string $field, array $rules, $condition)
    {
        $this->field = $field;
        $this->rules = $rules;
        $this->condition = $condition;
    }

    /**
     * Applies the rules if the condition is met.
     *
     * @param array $data Input data to check the condition
     * @return array|null Returns the rules as Rule objects if the condition is met, otherwise null
     */
    public function apply(array $data): ?array
    {
        if (!is_callable($this->condition)) {
            throw new \InvalidArgumentException("Condition for field '{$this->field}' is not a valid callable.");
        }

        if (call_user_func($this->condition, $data)) {
            return [$this->field => $this->resolveRules($this->rules)];
        }

        return null;
    }

    /**
     * Resolves rule names into Rule objects.
     *
     * @param array $rules Array of rule names or objects
     * @return array Array of Rule objects
     */
    protected function resolveRules(array $rules): array
    {
        return array_map(function ($rule) {
            if (is_string($rule)) {
                try {
                    return RuleProvider::resolve($rule, []);
                } catch (\InvalidArgumentException $e) {
                    throw new \InvalidArgumentException("Failed to resolve rule '{$rule}': {$e->getMessage()}");
                }
            } elseif ($rule instanceof Rule) {
                return $rule;
            } else {
                throw new \InvalidArgumentException("Invalid rule provided: " . print_r($rule, true));
            }
        }, $rules);
    }
}
