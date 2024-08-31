<?php

use Mousav1\Validify\Validator;
use PHPUnit\Framework\TestCase;

class IsUrlRuleTest extends TestCase
{
    public function testValidUrl()
    {
        $validator = new Validator(['website' => 'https://example.com']);
        $validator->setRules(['website' => ['url']]);
        $this->assertTrue($validator->validate());
    }

    public function testInvalidUrl()
    {
        $validator = new Validator(['website' => 'invalid-url']);
        $validator->setRules(['website' => ['url']]);
        $this->assertFalse($validator->validate());
        $this->assertNotEmpty($validator->getErrors());
    }
}
