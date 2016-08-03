<?php

namespace HexletPsrLinter;

use HexletPsrLinter\checks\FunctionCheck;
use HexletPsrLinter\checks\MethodCheck;
use PhpParser\Node\Stmt;

class ChecksTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckMethodName()
    {
        $testMethods = [
            'camelCase'         => [],
            'camelcase'         => [],
            'camelCamelCamel'   => [],
            '__construct'       => [],
            '__set'             => [],
            'CamelCase'         => [-1,'CamelCase'],
            'Camelcase'         => [-1,'Camelcase'],
            'camel_case'        => [-1,'camel_case'],
            '__camelcase'       => [-1,'__camelcase'],
        ];

        $check = new MethodCheck();

        foreach ($testMethods as $key => $val) {
            $this->assertEquals($check->validate(new Stmt\ClassMethod($key)), $val);
        }
    }

    public function testCheckFunctionName()
    {
        $testFunctions = [
            'camelCase'         => [],
            'camelcase'         => [],
            'camelCamelCamel'   => [],
            '__construct'       => [-1,'__construct'],
            '__set'             => [-1,'__set'],
            'CamelCase'         => [-1,'CamelCase'],
            'Camelcase'         => [-1,'Camelcase'],
            'camel_case'        => [-1,'camel_case'],
            '__camelcase'       => [-1,'__camelcase'],
        ];



        $check = new FunctionCheck();

        foreach ($testFunctions as $key => $val) {
            $val = $check->validate(new Stmt\Function_($key));
            $this->assertEquals($check->validate(new Stmt\Function_($key)), $val);
        }
    }
}
