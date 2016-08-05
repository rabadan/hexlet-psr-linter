<?php

namespace HexletPsrLinter\Report;

interface ReportPrintInterface
{
    public function createReport($logs);
}
