# Validify

## Introduction

**Validify** is a simple, extensible, and flexible validation library for PHP. It allows developers to define validation rules for their data inputs and easily validate them. This package provides a set of built-in rules and also supports custom validation rules.

## Features

- **Simple API**: Easy-to-use API for defining validation rules.
- **Customizable**: Allows the addition of custom validation rules.
- **Wildcard Support**: Supports validation of nested data structures using dot notation.
- **Pre-Validation Callbacks**: Supports execution of custom logic before validation.

## Installation

You can install the package via Composer:

```bash
composer require mousav1/validify
```


## Usage
#### Basic Validation

```php
use Mousav1\Validify\Validator;

$data = [
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'age' => 25,
];

$validator = new Validator($data);

$validator->setRules([
    'username' => ['required', 'alpha'],
    'email' => ['required', 'email'],
    'age' => ['required', 'numeric', 'min:18'],
]);

if ($validator->validate()) {
    echo "Validation passed!";
} else {
    print_r($validator->getErrors());
}

```


## Fluent Validation
#### The Validify package allows you to define rules using a fluent interface, making your validation logic more readable:

```php
use Mousav1\Validify\Validator;

$data = [
    'name' => 'prefix_name',
];

$validator = new Validator($data);

$validator->field('name')
    ->required()
    ->startsWith('prefix')
    ->applyRules();

if ($validator->validate()) {
    echo "Validation passed!";
} else {
    print_r($validator->getErrors());
}


```

## Custom Validation Rules
#### You can extend the validator with custom rules:

```php
use Mousav1\Validify\Validator;

Validator::extend('even', function () {
    return new class {
        public function passes($field, $value) {
            return $value % 2 === 0;
        }

        public function message($field) {
            return "{$field} must be an even number.";
        }
    };
});

$data = ['number' => 3];

$validator = new Validator($data);
$validator->setRules([
    'number' => ['even']
]);

if ($validator->validate()) {
    echo "Validation passed!";
} else {
    print_r($validator->getErrors());
}

```
## Custom Error Messages
#### You can define custom error messages for specific fields and rules:

```php

use Mousav1\Validify\Validator;

$data = [
    'username' => '',
    'email' => 'invalid-email',
];

$validator = new Validator($data);

$validator->setRules([
    'username' => ['required'],
    'email' => ['required', 'email'],
]);

$validator->setCustomMessages([
    'username.required' => 'The username field cannot be empty.',
    'email.email' => 'Please provide a valid email address.',
]);

if ($validator->validate()) {
    echo "Validation passed!";
} else {
    print_r($validator->getErrors());
}


```

## Field Aliases
#### You can set aliases for field names to provide user-friendly error messages:

```php

$validator->setAliases([
    'email' => 'Email Address'
]);

$validator->validate();

print_r($validator->getErrors()); // Errors will display "Email Address" instead of "email".

```

## Pre-Validation Callbacks
#### You can define callbacks that will run before the validation process starts:

```php

$validator->beforeValidate(function (&$data) {
    $data['username'] = strtolower($data['username']);
});

```

## Available Rules

- **required**
- **email**
- **min**
- **max**
- **numeric**
- **confirmed**
- **unique**
- **in**
- **between**
- **regex**
- **alpha**
- **optional**
- **required_with**
