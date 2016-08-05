<?php

namespace HexletPsrLinter\Report;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\ReaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @property $cli CLImate
*/

class ReportTxt implements ReportPrintInterface
{
    /**
     * print Json report
     */
    public function createReport($logs)
    {
        $output = '';
        /**
         * @var  $file string
         * @var  $msg Message
         */
        foreach ($logs as $file => $messages) {
            $output .= 'file: ' . $file . PHP_EOL;
            foreach ($messages as $msg) {
                $output .=
                    sprintf(
                        "%-5s%-10s%-25s%-60s",
                        $msg->getLine(),
                        $msg->getLevel(),
                        $msg->getName(),
                        $msg->getMessage()
                    ) . PHP_EOL;
            }
        }

        return $output;
    }
}
