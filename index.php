<?php

require __DIR__ . '/vendor/autoload.php';

use Mousav1\Validify\Validator;

$validator = new Validator([
    'email' => 'example@example.com',
]);

// Set validation rules
$validator->setRules([
    'email' => ['required', 'email'],
]);

// Validate the data
if ($validator->validate()) {
    echo "Validation passed!";
} else {
    print_r($validator->getErrors());
}
