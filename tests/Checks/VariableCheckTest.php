<?php

namespace HexletPsrLinter\Checks;

use PhpParser\Node;

class VariableCheckTest extends \PHPUnit_Framework_TestCase
{
    public function testVariableCheckGood()
    {
        $validData = [
            'camel_case'        => 'camelCase',
            'camel_case_case'   => 'camelCaseCase',
            'camel_case_case_case_case'   => 'camelCaseCaseCaseCase',
        ];

        foreach ($validData as $val => $fixVal) {
            $chekFix = new VariableCheck();
            $data = new Node\Expr\Variable($val);
            $this->assertFalse($chekFix->validate($data));
            $chekFix->modification($data);
            $this->assertEquals($data->name, $fixVal);
            $this->assertNotEquals($chekFix->getErrors(), []);

            $chekNoFix = new VariableCheck();
            $data = new Node\Expr\Variable($val);
            $this->assertFalse($chekNoFix->validate($data));
            $this->assertNotEquals($data->name, $fixVal);
            $this->assertNotEquals($chekNoFix->getErrors(), []);
        }
    }

    public function testVariableCheckBad()
    {
        $invalidData = [
            '_camelcase',
            'camelcase_',
            'camel_CamelCamel',
            'camel__Camel',
            '_camelCase',
            'camelCase_'
        ];

        foreach ($invalidData as $val) {
            $chekNoFix = new VariableCheck();
            $this->assertFalse($chekNoFix->validate(new Node\Expr\Variable($val)));
            $this->assertNotEquals($chekNoFix->getErrors(), []);
        }
    }
}
