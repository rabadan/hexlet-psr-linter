<?php

namespace HexletPsrLinter\Report\Format;

use Symfony\Component\Yaml\Yaml;

class ReportYaml implements ReportPrintInterface
{
    /**
     * print Yaml report
     */
    public function createReport($logs)
    {
        $yaml = new Yaml();
        return $yaml->dump($logs);
    }
}
