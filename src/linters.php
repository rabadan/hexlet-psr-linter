<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Checks\SideEffectsCheck;
use HexletPsrLinter\Checks\VariableCheck;
use HexletPsrLinter\Exceptions\SaveFileException;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use HexletPsrLinter\Visitor\NodeVisitor;

function makeLinter()
{
    return function ($codeFile, $params) {
        $modifyData = isset($params['fix'])?$params['fix']:false;
        $resultLint = [];
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new NodeVisitor([
            new MethodCheck(),
            new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)*$', 'No camel case function name'),
            new VariableCheck(),
            new SideEffectsCheck(),
        ], $modifyData);

        $traverser->addVisitor($visitor);

        try {
            $stmts = $parser->parse($codeFile);
            $traverser->traverse($stmts);

            $resultLint = $visitor->getErrors();

            // если у нас были изменеия в коде, то мы генерирум новый код
            if ($visitor->isDataHaveChanged()) {
                $prettyPrinter = new PrettyPrinter\Standard();
                $codeFile = "<?php\n{$prettyPrinter->prettyPrint($stmts)}\n";
            }
        } catch (\PhpParser\Error $e) {
            $resultLint[]= new Message(0, Report::LOG_LEVEL_ERROR, "(Parse Error)", $e->getMessage());
        }
        finally {
            return ["errors" => $resultLint, "codeFile" => $codeFile];
        }
    };
}

/**
 * @param $linter
 * @param $params
 * @return array
 */
function fileLinter($linter, $params)
{
    $files = getFilesPath($params['path']);

    $resultPathLint = array_map(function ($file) use ($linter, $params) {
        $resultLint = $linter(getFileContent($file), $params);
        if (isset($params['fix']) && $params['fix']) {
            try {
                writeFileContent($file, $resultLint['codeFile']);
            } catch (SaveFileException $e) {
                $resultLint['errors'][$file][] = new Message(
                    0,
                    Report::LOG_LEVEL_ERROR,
                    "(Save fix file error)",
                    $e->getMessage()
                );
            }
        }

        return [$file => $resultLint['errors']];
    }, $files);

    return $resultPathLint;
}
