<?php

namespace HexletPsrLinter\Check;

use HexletPsrLinter\Checks\MethodCheck;
use PhpParser\Node\Stmt;

class MethodCheckTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckMethodNameValid()
    {
        $validDate = [
            'camelCase',
            'camelcase',
            'camelCamelCamel',
            '__construct',
            '__set',
        ];

        $check = new MethodCheck();

        foreach ($validDate as $val) {
            $this->assertTrue($check->validate(new Stmt\ClassMethod($val)));
        }

        $this->assertEquals($check->getErrors(), []);
    }

    public function testCheckMethodNameInvalid()
    {
        $invalidData = [
            'CamelCase',
            'Camelcase',
            'camel_case',
            '__camelcase'
        ];

        $check = new MethodCheck();

        foreach ($invalidData as $val) {
            $this->assertFalse($check->validate(new Stmt\ClassMethod($val)));
        }

        $this->assertNotEquals($check->getErrors(), []);
    }
}
