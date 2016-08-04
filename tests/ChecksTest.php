<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use PhpParser\Node\Stmt;

class ChecksTest extends \PHPUnit_Framework_TestCase
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

        $invalidData = [
            'CamelCase',
            'Camelcase',
            'camel_case',
            '__camelcase'
        ];

        $check = new MethodCheck();

        foreach ($validDate as $val) {
            $this->assertTrue($check->validate(new Stmt\ClassMethod($val)));
        }

        foreach ($invalidData as $val) {
            $this->assertFalse($check->validate(new Stmt\ClassMethod($val)));
        }
    }

    public function testCheckRegexName()
    {
        $validDate = [
            'camelCase',
            'camelcase',
            'camelCamelCamel'
        ];

        $invalidData = [
            '__construct',
            '__set',
            'CamelCase',
            'Camelcase',
            'camel_case',
            '__camelcase'
        ];

        $check = new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)+$', 'No camel case function name');

        foreach ($validDate as $val) {
            $this->assertTrue($check->validate(new Stmt\Function_($val)));
        }

        foreach ($invalidData as $val) {
            $this->assertFalse($check->validate(new Stmt\Function_($val)));
        }
    }
}
