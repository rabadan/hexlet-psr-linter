<?php

namespace HexletPsrLinter\Linter;

use function HexletPsrLinter\linter;

class LinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterLintGood()
    {
        $linter = linter();
        $codeGood = file_get_contents(__DIR__ . "/../fixtures/good/good.php");
        $result = $linter($codeGood, []);
        $this->assertEquals($result['errors'], []);
    }

    public function testLinterLintBad()
    {
        $linter = linter();
        $codeBad = file_get_contents(__DIR__ . "/../fixtures/bad/bad.php");
        $result = $linter($codeBad, []);
        $this->assertNotEquals($result['errors'], []);
    }
}
