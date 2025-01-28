<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\Rule;

class Field
{
    /**
     * @var Validator The validator instance.
     */
    protected Validator $validator;

    /**
     * @var string The name of the field.
     */
    protected string $field;

    /**
     * @var array The array of rules applied to the field.
     */
    protected array $rules = [];

    /**
     * Field constructor.
     *
     * @param Validator $validator The validator instance.
     * @param string $field The name of the field.
     */
    public function __construct(Validator $validator, string $field)
    {
        $this->validator = $validator;
        $this->field = $field;
    }

    /**
     * Magic method to handle dynamic method calls for adding rules.
     *
     * @param string $method The name of the method called.
     * @param array $parameters The parameters passed to the method.
     * @return static
     */
    public function __call(string $method, array $parameters): static
    {
        $this->rules[] = $this->createRule($method, $parameters);
        return $this;
    }

    /**
     * Creates a rule based on the method name and parameters.
     *
     * @param string $method The name of the rule method.
     * @param array $parameters The parameters for the rule.
     * @return Rule
     * @throws \InvalidArgumentException If the rule cannot be created.
     */
    protected function createRule(string $method, array $parameters): Rule
    {
        try {
            return $this->validator->getRuleProvider()->resolve($method, $parameters);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Failed to create rule '{$method}' for field '{$this->field}'. Error: {$e->getMessage()}");
        }
    }

    /**
     * Applies the rules to the validator.
     *
     * @return void
     */
    public function applyRules(): void
    {
        $this->validator->setRules([$this->field => $this->rules]);
    }
}
