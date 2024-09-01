<?php

namespace Mousav1\Validify;

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
        if (is_callable($this->condition) && call_user_func($this->condition, $data)) {
            // Convert rule names to Rule objects
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
        return array_map(function($rule) {
            if (is_string($rule)) {
                // Convert rule names to Rule objects
                return RuleProvider::resolve($rule, []);
            }
            return $rule;
        }, $rules);
    }
}
