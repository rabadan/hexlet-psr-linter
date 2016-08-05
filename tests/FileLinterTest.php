<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Report\Report;
use PhpParser\ParserFactory;

class FileLinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterRunGood()
    {
        $report = new Report();
        $linter = new FileLinter(new Linter(), $report);
        $result = $linter->lint(__DIR__."/fixtures/good");
        $this->assertEquals($result->getLogs(), []);
    }

    public function testLinterRunBad()
    {
        $report = new Report();
        $linter = new FileLinter(new Linter(), $report);
        $result = $linter->lint(__DIR__."/fixtures/bad/bad.php");
        $this->assertNotEquals($result->getLogs(), []);
    }
}
