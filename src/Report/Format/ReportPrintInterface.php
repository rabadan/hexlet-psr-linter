<?php

namespace HexletPsrLinter\Report\Format;

interface ReportPrintInterface
{
    public function createReport($logs);
}
