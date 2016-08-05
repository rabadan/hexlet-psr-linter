<?php

namespace HexletPsrLinter\Report;

interface ReportInterface
{
    public function __construct();
    public function addLog($file, $message);
    public function addLogs($file, $messages);
    public function getLogs();
    public function isEmpty();
    public function createReport();
}
