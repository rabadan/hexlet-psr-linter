<?php

namespace hexletPsrLinter;

use function hexletPsrLinter\checkFunctionName;

class CheckersTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckFunctionName()
    {
        $testArr = [
            'camelCase' => true,
            'camelcase' => true,
            'camelCamelCamel' => true,
            'CamelCase' => false,
            'Camelcase' => false,
            'camel_case' => false
        ];

        foreach ($testArr as $key => $val) {
            $this->assertEquals(checkFunctionName($key), $val);
        }
    }
}
