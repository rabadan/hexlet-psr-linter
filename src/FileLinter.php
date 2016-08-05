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

    public function __construct(Linter $linter, ReportInterface $report)
    {
        $this->linter = $linter;
        $this->report = $report;
    }

    public function lint($path)
    {
        $files = getFilesPath($path);

        array_map(function ($file) {
            $error = $this->linter->lint(getFileContent($file));
            if (!empty($error)) {
                $this->report->addLogs($file, $error);
            }
        }, $files);

        return $this->getReport();
    }

    public function getReport()
    {
        return $this->report;
    }
}
