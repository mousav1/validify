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
        try {
            return RuleProvider::resolve($method, $parameters);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Failed to create rule '{$method}' for field '{$this->field}'. Error: {$e->getMessage()}");
        }
    }

    public function applyRules(): void
    {
        $this->validator->setRules([$this->field => $this->rules]);
    }
}
