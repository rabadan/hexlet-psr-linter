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

    public function testReport()
    {
        $report = new Report();
        $report->addLog('test.php', $this->message);
        $this->assertEquals($report->getLogs(), ['test.php'=>[$this->message]]);
    }

    public function testReportYaml()
    {
        $report = new ReportYaml();
        $report->addLog('test.php', $this->message);
        $this->assertEquals(Yaml::dump($report->getLogs()), $report->createReport());
    }

    public function testReportJson()
    {
        $report = new ReportJson();
        $report->addLog('test.php', $this->message);
        $this->assertEquals(json_encode($report->getLogs()), $report->createReport());
    }
}
