<?php

namespace HexletPsrLinter\Report;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\ReaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @property $cli CLImate
*/

class ReportYaml implements ReportPrintInterface
{
    /**
     * print Yaml report
     */
    public function createReport($logs)
    {
        return Yaml::dump($logs);
    }
}
