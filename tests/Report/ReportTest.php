<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use HexletPsrLinter\Report\ReportJson;
use HexletPsrLinter\Report\ReportYaml;
use Symfony\Component\Yaml\Yaml;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    public $message;

    public function setUp()
    {
        $this->message = new Message(1, Report::LOG_LEVEL_ERROR, 'test', 'message');
    }

    public function testEmpty()
    {
        $report = new Report('txt');
        $this->assertTrue($report->isEmpty());
        $report->addLog('test.php', $this->message);
        $this->assertFalse($report->isEmpty());
    }

    public function testLogs()
    {
        $report = new Report('txt');
        $report->addLog('test.php', $this->message);
        $this->assertEquals($report->getLogs(), ['test.php'=>[$this->message]]);
    }

    public function testReportTxt()
    {
        $report = new Report('txt');
        $report->addLog('test.php', $this->message);
        $val = "file: test.php" .
            PHP_EOL .
            "1    error     test                     message                                                     " .
            PHP_EOL;
        $this->assertEquals($val, $report->printFormat());
    }

    public function testReportYaml()
    {
        $report = new Report('yml');
        $report->addLog('test.php', $this->message);
        $this->assertEquals(Yaml::dump($report->getLogs()), $report->printFormat());
    }

    public function testReportJson()
    {
        $report = new Report('json');
        $report->addLog('test.php', $this->message);
        $this->assertEquals(json_encode($report->getLogs()), $report->printFormat());
    }
}
