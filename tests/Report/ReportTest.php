<?php

namespace HexletPsrLinter\Report;

use Symfony\Component\Yaml\Yaml;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    public $message;

    public function setUp()
    {
        $this->message = [
            'line'      => 1,
            'logLevel'  => Report::LOG_LEVEL_ERROR,
            'name'      => 'test',
            'message'   => 'message'
        ];
    }

    public function testEmpty()
    {
        $report = new Report([]);
        $this->assertTrue($report->isEmpty());
        $report->addLog('test.php', $this->message);
        $this->assertFalse($report->isEmpty());
    }

    public function testLogs()
    {
        $report = new Report([]);
        $report->addLog('test.php', $this->message);
        $this->assertEquals($report->getLogs(), ['test.php'=>[$this->message]]);
    }

    public function testReportTxt()
    {
        $report = new Report([]);
        $report->addLog('test.php', $this->message);
        $val = "file: test.php" .
            PHP_EOL .
            "1    error     test                     message                                                     " .
            PHP_EOL . PHP_EOL;
        $this->assertEquals($val, $report->getReport());
    }

    public function testReportYaml()
    {
        $report = new Report([]);
        $report->addLog('test.php', $this->message);
        $yaml = new Yaml();
        $this->assertEquals($yaml->dump($report->getLogs()), $report->getReport('yml'));
    }

    public function testReportJson()
    {
        $report = new Report([]);
        $report->addLog('test.php', $this->message);
        $this->assertEquals(json_encode($report->getLogs()), $report->getReport('json'));
    }
}
