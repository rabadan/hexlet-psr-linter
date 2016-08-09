<?php

namespace HexletPsrLinter\Report\Format;

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
         * @var  $msg []
         */
        foreach ($logs as $file => $messages) {
            $output .= 'file: ' . $file . PHP_EOL;
            foreach ($messages as $msg) {
                $output .=
                    sprintf(
                        "%-5s%-10s%-25s%-60s",
                        $msg['line'],
                        $msg['logLevel'],
                        $msg['name'],
                        $msg['message']
                    ) . PHP_EOL;
            }
            $output .= PHP_EOL;
        }

        return $output;
    }
}
