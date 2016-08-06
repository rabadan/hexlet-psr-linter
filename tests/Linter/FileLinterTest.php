<?php

namespace HexletPsrLinter\Linter;

use HexletPsrLinter\Report\Report;

class FileLinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterRunGood()
    {
        $linter = new FileLinter(new Linter());
        $result = $linter->lint(__DIR__ . "/../fixtures/good");
        $report = new Report($result);
        $this->assertEquals($report->getLogs(), []);
    }

    public function testLinterRunBad()
    {
        $linter = new FileLinter(new Linter());
        $result = $linter->lint(__DIR__ . "/../fixtures/bad/bad.php");
        $report = new Report($result);
        $this->assertNotEquals($report->getLogs(), []);
    }
}
