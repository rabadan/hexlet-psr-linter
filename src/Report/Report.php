<?php

namespace HexletPsrLinter\Report;

use HexletPsrLinter\Report\Format\ReportJson;
use HexletPsrLinter\Report\Format\ReportTxt;
use HexletPsrLinter\Report\Format\ReportYaml;

class Report
{
    const LOG_LEVEL_ERROR = 'error';
    const LOG_LEVEL_WARNING = 'warning';
    const LOG_LEVEL_INFO = 'info';

    private $logs;
    private $reportClass = [
        'txt'  => ReportTxt::class,
        'yml'  => ReportYaml::class,
        'json' => ReportJson::class,
    ];


    /**
     * Report constructor.
     */
    public function __construct($logs)
    {
        $this->logs = [];
        $this->loadReport($logs);
    }

    /**
     * @param $report mixed
     */
    private function loadReport($report)
    {
        foreach ($report as $log) {
            foreach ($log as $file => $message) {
                if (empty($message)) {
                    continue;
                }
                $this->logs[$file] = $message;
            }
        }
    }

    /**
     * @param $file string
     * @param $message Message
     */
    public function addLog($file, $message)
    {
        $this->logs[$file][] = $message;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function isEmpty()
    {
        return empty($this->logs);
    }

    public function getReport($format = 'txt')
    {
        if (!array_key_exists($format, $this->reportClass)) {
            $format = 'txt';
        }
        $formatReport = new $this->reportClass[$format];

        return $formatReport->createReport($this->getLogs());
    }
}
