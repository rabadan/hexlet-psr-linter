<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\RegexCheck;
use PhpParser\Node\Stmt;

class RegexCheckTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckRegexNameValid()
    {
        $validDate = [
            'camelCase',
            'camelcase',
            'camelCamelCamel'
        ];

        $check = new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)+$', 'No camel case function name');

        foreach ($validDate as $val) {
            $this->assertTrue($check->validate(new Stmt\Function_($val)));
        }
    }

    public function testCheckRegexNameInvalid()
    {
        $invalidData = [
            '__construct',
            '__set',
            'CamelCase',
            'Camelcase',
            'camel_case',
            '__camelcase'
        ];

        $check = new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)+$', 'No camel case function name');

        foreach ($invalidData as $val) {
            $this->assertFalse($check->validate(new Stmt\Function_($val)));
        }
    }
}
