<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use HexletPsrLinter\Visitor\NodeVisitor;

function linter()
{
    return function ($codeFile) {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new NodeVisitor([
            new MethodCheck,
            new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)+$', 'No camel case function name'),
            new RegexCheck('Expr_Variable', '^[a-z]+([A-Z]?[a-z]+)+$', 'No camel case Variable name')
        ]);

        $traverser->addVisitor($visitor);

        try {
            $stmts = $parser->parse($codeFile);
            $traverser->traverse($stmts);
            $errors = $visitor->getErrors();
        } catch (\PhpParser\Error $e) {
            $errors[] = new Message(0, Report::LOG_LEVEL_ERROR, "(Parse Error)", $e->getMessage());
        } finally {
            return $errors;
        }
    };
}

function fileLinter($linter, $path)
{
    $files = getFilesPath($path);

    $result = array_map(function ($file) use ($linter) {
        return [$file => $linter(getFileContent($file))];
    }, $files);

    return $result;
}
