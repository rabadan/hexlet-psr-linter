<?php

namespace HexletPsrLinter;

use PhpParser\ParserFactory;

class FileLinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterRunGood()
    {
        $linter = new FileLinter(new Linter());
        $result = $linter->lint(__DIR__."/fixtures/good");
        $this->assertEquals($result->getLogs(), []);
    }

    public function testLinterRunBad()
    {
        $linter = new FileLinter(new Linter());
        $result = $linter->lint(__DIR__."/fixtures/bad/bad.php");
        $this->assertNotEquals($result->getLogs(), []);
    }
}
