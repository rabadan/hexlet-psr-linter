<?php

namespace HexletPsrLinter\Report\Format;

/**
 * @property $cli CLImate
*/

class ReportJson implements ReportPrintInterface
{
    /**
     * print Json report
     */
    public function createReport($logs)
    {
        return json_encode($logs);
    }
}
