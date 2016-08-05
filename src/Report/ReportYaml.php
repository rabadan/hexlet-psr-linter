<?php

namespace HexletPsrLinter\Report;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\ReaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @property $cli CLImate
*/

class ReportYaml extends Report implements ReportInterface
{
    /**
     * print Yaml report
     */
    public function createReport()
    {
        return Yaml::dump($this->getLogs());
    }
}
