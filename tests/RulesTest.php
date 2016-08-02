<?php

namespace HexletPsrLinter;

class RulesTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckFunctionName()
    {
        $testArr = [
            'camelCase'         => true,
            'camelcase'         => true,
            'camelCamelCamel'   => true,
            '__construct'       => true,
            '__set'             => true,
            'CamelCase'         => false,
            'Camelcase'         => false,
            'camel_case'        => false,
            '__camelcase'       => false
        ];

        $rules = new Rules();

        foreach ($testArr as $key => $val) {
            $this->assertEquals($rules->validateFunctionName($key), $val);
        }
    }
}
