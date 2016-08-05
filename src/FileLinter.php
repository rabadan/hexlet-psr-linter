<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Report\Report;

/**
 * Class FileLinter
 * @package HexletPsrLinter
 */

class FileLinter
{
    private $linter;
    private $report;

    public function __construct($linter)
    {
        $this->linter = $linter;
        $this->report = new Report();
    }

    public function lint($path)
    {
        $files = getFilesPath($path);

        array_map(function($file) {
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
