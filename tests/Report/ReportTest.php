<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use Symfony\Component\Yaml\Yaml;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    public $report;
    public $message;

    public function setUp()
    {
        $this->report = new Report();
        $this->message = new Message(1, Report::LOG_LEVEL_ERROR, 'test', 'message');
        $this->report->addLog('test.php', $this->message);
    }

    public function testReport()
    {
        $this->assertEquals($this->report->getLogs(), ['test.php'=>[$this->message]]);
    }

    public function testReportYaml()
    {
        $ymlReport = $this->report->createReport('yml');
        $this->assertEquals(Yaml::dump($this->report->getLogs()), $ymlReport);
    }

    public function testReportJson()
    {
        $jsonReport = $this->report->createReport('json');
        $this->assertEquals(json_encode($this->report->getLogs()), $jsonReport);
    }
}
