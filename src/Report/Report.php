<?php

namespace HexletPsrLinter\Report;

use HexletPsrLinter\Report\Format\ReportJson;
use HexletPsrLinter\Report\Format\ReportTxt;
use HexletPsrLinter\Report\Format\ReportYaml;
use League\CLImate\CLImate;

/**
 * @property $cli CLImate
*/

class Report
{
    const LOG_LEVEL_ERROR = 'error';
    const LOG_LEVEL_WARNING = 'warning';
    const LOG_LEVEL_FIXED = 'fixed';

    private $logs;
    private $cli;
    private $formatReport;
    private $reportClass = [
        'txt'  => ReportTxt::class,
        'yml'  => ReportYaml::class,
        'json' => ReportJson::class,
    ];


    /**
     * Report constructor.
     */
    public function __construct($logs, $format = 'txt')
    {
        if (!array_key_exists($format, $this->reportClass)) {
            $format = 'txt';
        }
        $this->formatReport = new $this->reportClass[$format];
        $this->logs = [];
        $this->loadReport($logs);
        $this->cli = new CLImate();
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

    public function printFormat()
    {
        return $this->formatReport->createReport($this->getLogs());
    }
}
