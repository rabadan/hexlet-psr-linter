<?php

namespace HexletPsrLinter\Checks;

use PhpParser\Node;

class FixVariableCheckTest extends \PHPUnit_Framework_TestCase
{
    public function testFixVariableCheckGood()
    {
        $validData = [
            'camel_case'        => 'camelCase',
            'camel_case_case'   => 'camelCaseCase',
            'camel_case_case_case_case'   => 'camelCaseCaseCaseCase',
        ];

        foreach ($validData as $val => $fixVal) {
            $chekFix = new FixVariableCheck();
            $data = new Node\Expr\Variable($val);
            $this->assertFalse($chekFix->validate($data, true));
            $this->assertEquals($data->name, $fixVal);
            $this->assertNotEquals($chekFix->getErrors(), []);

            $chekNoFix = new FixVariableCheck();
            $data = new Node\Expr\Variable($val);
            $this->assertFalse($chekNoFix->validate($data, false));
            $this->assertNotEquals($data->name, $fixVal);
            $this->assertNotEquals($chekNoFix->getErrors(), []);
        }
    }

    public function testFixVariableCheckBad()
    {
        $invalidData = [
            'camelcase',
            '_camelcase',
            'camelcase_',
            'camelCamelCamel',
            'camelCamelCamel',
            '_camelCase',
            'camelCase_'
        ];

        foreach ($invalidData as $val) {
            $chekNoFix = new FixVariableCheck();
            $this->assertTrue($chekNoFix->validate(new Node\Expr\Variable($val), false));
            $this->assertEquals($chekNoFix->getErrors(), []);
        }
    }
}
