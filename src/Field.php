<?php

namespace Mousav1\Validify;

use Mousav1\Validify\Rules\Rule;

class Field
{
    protected Validator $validator;
    protected string $field;
    protected array $rules = [];

    public function __construct(Validator $validator, string $field)
    {
        $this->validator = $validator;
        $this->field = $field;
    }

    public function __call(string $method, array $parameters): static
    {
        $rule = $this->createRule($method, $parameters);
        $this->rules[] = $rule;
        return $this;
    }

    protected function createRule(string $method, array $parameters): Rule
    {
        $ruleClass = RuleProvider::resolve($method, $parameters);

        // Check if it's a custom rule
        if (isset($customRules[$method])) {
            return call_user_func_array($customRules[$method], $parameters);
        }

        return $ruleClass;
    }

    public function applyRules(): void
    {
        $this->validator->setRules([$this->field => $this->rules]);
    }
}