<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Report\Report;
use HexletPsrLinter\Report\ReportInterface;

/**
 * Class FileLinter
 * @package HexletPsrLinter
 */

class FileLinter
{
    private $linter;
    private $report;

    public function __construct(Linter $linter, $report)
    {
        $this->linter = $linter;
        $this->report = $report;
    }

    public function lint($path)
    {
        $files = getFilesPath($path);

        $result = array_map(function ($file) {
            return [$file => $this->linter->lint(getFileContent($file))];
        }, $files);

        $this->report->addLogs($result);

        return $this->getReport();
    }

    public function getReport()
    {
        return $this->report;
    }
}
