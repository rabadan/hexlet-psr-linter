<?php

namespace HexletPsrLinter\Report;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\ReaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @property $cli CLImate
*/

class ReportJson extends Report implements ReportInterface
{
    /**
     * print Json report
     */
    public function createReport()
    {
        return json_encode($this->getLogs());
    }
}
