# Validify

## Table of Contents

- [Validify](#validify)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Features](#features)
  - [Installation](#installation)
  - [Quick Start](#quick-start)
    - [Basic Validation](#basic-validation)
    - [Fluent Validation](#fluent-validation)
  - [Advanced Usage](#advanced-usage)
    - [Custom Validation Rules](#custom-validation-rules)
    - [Conditional Validation](#conditional-validation)
    - [Date and Time Validation](#date-and-time-validation)
      - [Date Format](#date-format)
      - [After and Before Rules](#after-and-before-rules)
    - [Custom Error Messages](#custom-error-messages)
    - [Field Aliases](#field-aliases)
    - [Pre-Validation Callbacks](#pre-validation-callbacks)
  - [Available Rules](#available-rules)
  - [Contributing](#contributing)
    - [Steps to Contribute:](#steps-to-contribute)
  - [License](#license)

## Introduction

**Validify** is a lightweight and extensible PHP validation library that simplifies validating user inputs. Whether you're building an API, a form, or any application requiring data validation, Validify provides a powerful and intuitive way to handle it.

## Features

- **Simple API**: Easy-to-use API for defining and executing validation rules.
- **Extensible**: Define custom validation rules to fit specific use cases.
- **Pre-Validation Callbacks**: Execute custom logic before validation.
- **Conditional Validation**: Dynamically apply rules based on conditions.
- **Nested Data Support**: Validate complex data structures using dot notation.
- **Comprehensive Built-in Rules**: Includes a wide range of predefined validation rules.

## Installation

To install the package, use Composer:

```bash
composer require mousav1/validify
```

## Quick Start

### Basic Validation

Here is an example of simple validation using Validify:

```php
use Mousav1\Validify\Validator;

$data = [
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'age' => 25,
];

$validator = new Validator($data, [
    'username' => ['required', 'alpha'],
    'email' => ['required', 'email'],
    'age' => ['required', 'numeric', 'min:18'],
]);

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

### Fluent Validation

Define validation rules using a fluent interface for better readability:

```php
use Mousav1\Validify\Validator;

$data = [
    'name' => 'prefix_name',
];

$validator = new Validator($data);

$validator->field('name')
    ->required()
    ->applyRules();

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

## Advanced Usage

### Custom Validation Rules

Extend Validify with your own custom rules:

```php
use Mousav1\Validify\Validator;

Validator::extend('even', function () {
    return new class extends \Mousav1\Validify\Rules\Rule {
        public function passes($field, $value, array $data): bool
        {
            return $value % 2 === 0;
        }
        public function name(): string
        {
            return 'even';
        }
        public function message($field): string
        {
            return "{$field} must be an even number.";
        }
    };
});

$data = ['number' => 3];

$validator = new Validator($data, [
    'number' => ['even']
]);

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

### Conditional Validation

Apply rules dynamically based on conditions:

```php
$data = [
    'age' => 20,
    'license' => ''
];

$validator = new Validator($data);

$validator->addConditionalRule('license', ['required'], function ($data) {
    return $data['age'] > 18;
});

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

### Date and Time Validation

Validate date and time fields using predefined rules:

#### Date Format

```php
$data = [
    'birthdate' => '2024-09-01',
];

$validator = new Validator($data, [
    'birthdate' => ['required', 'date_format:Y-m-d'],
]);

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

#### After and Before Rules

```php
$data = [
    'start_date' => '2024-01-01',
    'end_date' => '2024-02-01',
];

$validator = new Validator($data, [
    'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
]);

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

### Custom Error Messages

Define custom error messages for specific rules:

```php
use Mousav1\Validify\Validator;

$data = [
    'username' => '',
    'email' => 'invalid-email',
];

$validator = new Validator($data, [
    'username' => ['required'],
    'email' => ['required', 'email'],
]);

$validator->setCustomMessages([
    'username.required' => 'The username field cannot be empty.',
    'email.email' => 'Please provide a valid email address.',
]);

if (!$validator->validate()) {
    print_r($validator->getErrors());
}
```

### Field Aliases

Use aliases for more readable error messages:

```php
$validator->setAliases([
    'email' => 'Email Address'
]);

$validator->validate();

print_r($validator->getErrors());
```

### Pre-Validation Callbacks

Execute logic before validation starts:

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
- **url**
- **in**
- **between**
- **regex**
- **alpha**
- **optional**
- **required_with**
- **array**
- **integer**
- **boolean**
- **not_in**
- **uppercase**
- **lowercase**
- **json**
- **date_format**
- **after**
- **before**

## Contributing

Contributions are welcome! Feel free to submit a pull request or open an issue.

### Steps to Contribute:

1. Fork the repository.
2. Create a new branch for your feature/bugfix.
3. Write clear and descriptive commit messages.
4. Submit a pull request with detailed explanation.

## License

This package is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
