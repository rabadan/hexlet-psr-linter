<?php

namespace HexletPsrLinter;

class PsrLinterTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckFunctionName()
    {

        $linter = new PsrLinter();
        $result = $linter->lint(["path"=>__DIR__."/fixtures/good/good.php"]);
        $this->assertEquals($result, []);

        $result = $linter->lint(["path"=>__DIR__."/fixtures/bad/bad.php"]);
        $this->assertEquals(
            $result, [
            __DIR__."/fixtures/bad/bad.php"=>[
                    0 => "Name - not valid name",
                    1 => "NameGood - not valid name",
                    2 => "bad_bad_name - not valid name"
                ]
            ]
        );
    }
}
