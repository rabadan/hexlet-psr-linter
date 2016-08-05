<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class Linter
{
    private $report;

    public function __construct()
    {
        $this->report = new Report();
    }

    /**
     * @return Report
     */
    public function getReport(): Report
    {
        return $this->report;
    }


    public function run($cmd)
    {
        $resultCode = 0;

        $files = getFilesPath($cmd['path']);

        foreach ($files as $file) {
            $error = $this->lint(getFileContent($file));
            if (!empty($error)) {
                $this->report->addLogs($file, $error);
                $resultCode = 1;
            }
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
        $stmts = $parser->parse($codeFile);
        $traverser->traverse($stmts);
        return $visitor->getErrors();
    }
}
