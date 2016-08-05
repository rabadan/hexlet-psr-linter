<?php

namespace HexletPsrLinter\Report;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\ReaderInterface;
use Symfony\Component\Yaml\Yaml;

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
