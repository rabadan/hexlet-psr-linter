<?php

namespace HexletPsrLinter;

use PhpParser\ParserFactory;

class LinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterLintGood()
    {
        $linter = new Linter();
        $codeGood = file_get_contents(__DIR__."/fixtures/good/good.php");
        $this->assertEquals($linter->lint($codeGood), []);
    }

    public function testLinterLintBad()
    {
        $linter = new Linter();
        $codeBad = file_get_contents(__DIR__."/fixtures/bad/bad.php");
        $this->assertNotEquals($linter->lint($codeBad), []);
    }
}
