<?php

namespace HexletPsrLinter;

use HexletPsrLinter\checks\FunctionCheck;
use HexletPsrLinter\checks\MethodCheck;
use PhpParser\Node\Stmt;

class ChecksTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckMethodNameValid()
    {
        $testMethods = [
            'camelCase',
            'camelcase',
            'camelCamelCamel',
            '__construct',
            '__set',
        ];

        $check = new MethodCheck();

        foreach ($testMethods as $val) {
            $this->assertTrue($check->validate(new Stmt\ClassMethod($val)));
        }
    }

    public function testCheckMethodNameInvalid()
    {
        $testMethods = [
            'CamelCase',
            'Camelcase',
            'camel_case',
            '__camelcase'
        ];

        $check = new MethodCheck();

        foreach ($testMethods as $val) {
            $this->assertFalse($check->validate(new Stmt\ClassMethod($val)));
        }
    }

    public function testCheckFunctionNameValid()
    {
        $testFunctions = [
            'camelCase',
            'camelcase',
            'camelCamelCamel'
        ];

        $check = new FunctionCheck();

        foreach ($testFunctions as $val) {
            $this->assertTrue($check->validate(new Stmt\Function_($val)));
        }
    }


    public function testCheckFunctionNameInvalid()
    {
        $testFunctions = [
            '__construct',
            '__set',
            'CamelCase',
            'Camelcase',
            'camel_case',
            '__camelcase'
        ];

        $check = new FunctionCheck();

        foreach ($testFunctions as $val) {
            $this->assertFalse($check->validate(new Stmt\Function_($val)));
        }
    }
}
