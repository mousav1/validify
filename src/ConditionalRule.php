<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\Rule;

class ConditionalRule
{
    /**
     * @var string The field to which the rules apply.
     */
    protected string $field;

    /**
     * @var array The rules to be applied.
     */
    protected array $rules;

    /**
     * @var callable The condition to check before applying the rules.
     */
    protected $condition;

    /**
     * ConditionalRule constructor.
     *
     * @param string $field The field to which the rules apply.
     * @param array $rules The rules to be applied.
     * @param callable $condition The condition to check before applying the rules.
     */
    public function __construct(string $field, array $rules, $condition)
    {
        $this->field = $field;
        $this->rules = $rules;
        $this->condition = $condition;
    }

    /**
     * Apply the rules to the data if the condition is met.
     *
     * @param array $data The data to which the rules are applied.
     * @return array|null The resolved rules if the condition is met, null otherwise.
     * @throws \InvalidArgumentException If the condition is not a valid callable.
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
     * Resolve the rules to their appropriate instances.
     *
     * @param array $rules The rules to be resolved.
     * @return array The resolved rules.
     * @throws \InvalidArgumentException If an invalid rule is provided.
     */
    protected function resolveRules(array $rules): array
    {
        return array_map(function ($rule) {
            if (is_string($rule)) {
                return RuleProvider::resolve($rule, []);
            } elseif ($rule instanceof Rule) {
                return $rule;
            }
            throw new \InvalidArgumentException("Invalid rule provided: " . print_r($rule, true));
        }, $rules);
    }
}
