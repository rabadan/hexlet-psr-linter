<?php

namespace HexletPsrLinter;

use Colors\Color;
use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class Linter
{

    public function run($cmd, $printReport = false)
    {
        $resultCode = 0;


        $files = getFilesPath($cmd['path']);
        $report = new Report();

        foreach ($files as $file) {
            $error = $this->lint(getFileContent($file));
            if (!empty($error)) {
                $report->addLogs($file, $error);
                $resultCode = 1;
            }
        }

        if ($resultCode && $printReport) {
            $report->createReport($cmd['format']);
        }

        return $resultCode;
    }


    public function lint($codeFile)
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new NodeVisitor([
            new MethodCheck,
            new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)+$', 'No camel case function name')
        ]);
        $traverser->addVisitor($visitor);

        try {
            // parse
            $stmts = $parser->parse($codeFile);

            // traverse
            $traverser->traverse($stmts);

            $error = $visitor->getErrors();
        } catch (\PhpParser\Error $e) {
            $error = [new Message(0, Report::LOG_LEVEL_ERROR, "(Parse Error)", $e->getMessage())];
        }

        return $error;
    }
}
