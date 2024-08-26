<?php

namespace Mousav1\Validify\Rules;

use Mousav1\Validify\Validator;

class RequiredWithRule extends Rule
{
    protected array $fields;
    
    public function __construct(...$fields)
    {
        $this->fields = $fields;
    }

    public function name(): string
    {
        return 'required_with';
    }

    public function passes($field, $value, $data): bool
    {
        foreach($this->fields as $field) {
            if($value === '' && $data[$field] !== '') {
                return false;
            }
        }

        return true;
    }

    public function message($field): string
    {

        $fields = Validator::aliases($this->fields);
        return "{$field} is required with {$fields} ";
    }
}