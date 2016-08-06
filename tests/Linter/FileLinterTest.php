<?php

namespace HexletPsrLinter\Linter;

use HexletPsrLinter\Report\Report;
use function HexletPsrLinter\linter;
use function HexletPsrLinter\fileLinter;

class FileLinterTest extends \PHPUnit_Framework_TestCase
{
    public function testLinterRunGood()
    {
        $result = fileLinter(linter(), __DIR__ . "/../fixtures/good");
        $report = new Report($result);
        $this->assertEquals($report->getLogs(), []);
    }

    public function testLinterRunBad()
    {
        $result = fileLinter(linter(), __DIR__ . "/../fixtures/bad/bad.php");
        $report = new Report($result);
        $this->assertNotEquals($report->getLogs(), []);
    }
}
