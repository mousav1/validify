<?php

require __DIR__ . '/vendor/autoload.php';

use Mousav1\Validify\Rules\EvenRule;
use Mousav1\Validify\Rules\Rule;
use Mousav1\Validify\Validator;

Validator::extend('startsWith', function ($prefix) {
    return new class($prefix) extends Rule {
        protected string $prefix;

        public function __construct(string $prefix)
        {
            $this->prefix = $prefix;
        }

        public function name(): string
        {
            return 'startsWith';
        }

        public function passes(string $field, mixed $value, array $input): bool
        {
            return strpos($value, $this->prefix) === 0;
        }

        public function message(string $field): string
        {
            return "{$field} must start with {$this->prefix}.";
        }
    };
});

$validator = new Validator([
    'email' => 'example@example.com',
    'name' => 'prefix 1lllllllllllllll',
]);

// Set validation rules
// $validator->setRules([
//     'email' => ['required', 'email'],
//     'name' => ['required', 'between:10,15'],
// ]);

$validator->field('name')
    ->required()
    ->startsWith('prefix')
    ->applyRules();
    
// Validate the data
if ($validator->validate()) {
    echo "Validation passed!";
} else {
    print_r($validator->getErrors());
}
