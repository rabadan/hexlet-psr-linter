<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {

        $linter = new Linter();
        $result = $linter->run(["path"=>__DIR__."/fixtures/good"]);
        $this->assertEquals($result, 0);

        $result = $linter->run(["path"=>__DIR__."/fixtures/bad/bad.php"]);

        $this->assertNotEquals($result, 0);
    }

    public function testLint()
    {
        $linter = new Linter();

        $codeGood = file_get_contents(__DIR__."/fixtures/good/good.php");

        $this->assertEquals($linter->lint($codeGood), []);

        $codeBad = file_get_contents(__DIR__."/fixtures/bad/bad.php");

        $this->assertNotEquals($linter->lint($codeBad), []);
    }
}
