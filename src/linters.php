<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\FixVariableCheck;
use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Checks\SideEffectsCheck;
use HexletPsrLinter\Exceptions\SaveFileException;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use HexletPsrLinter\Visitor\NodeVisitor;

function linter()
{
    return function ($codeFile, $params) {
        $fix = isset($params['fix'])?$params['fix']:false;

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new NodeVisitor([
            new MethodCheck(),
            new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)*$', 'No camel case function name'),
            new RegexCheck('Expr_Variable', '^[a-z]+([A-Z]?[a-z1-9]+)*$', 'No camel case Variable name'),
            new SideEffectsCheck(),
            new FixVariableCheck()
        ], $fix);


        $traverser->addVisitor($visitor);

        try {
            $stmts = $parser->parse($codeFile);
            $traverser->traverse($stmts);
            $errors = $visitor->getErrors();

            // если у нас были изменеия в коде, то мы генерирум новый код
            if (!empty($errors)) {
                $fixItems = array_filter($errors, function ($item) {
                    return $item->getLevel() == Report::LOG_LEVEL_FIXED;
                });

                if (!empty($fixItems)) {
                    $prettyPrinter = new PrettyPrinter\Standard();
                    $codeFile = "<?php\n{$prettyPrinter->prettyPrint($stmts)}\n";
                }
            }
        } catch (\PhpParser\Error $e) {
            $errors[]= new Message(0, Report::LOG_LEVEL_ERROR, "(Parse Error)", $e->getMessage());
        }
        finally {
            return ["errors" => $errors, "codeFile" => $codeFile];
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

    $result = array_map(function ($file) use ($linter, $params) {
        $result = $linter(getFileContent($file), $params);

        if (isset($params['fix']) && $params['fix']) {
            //eval(\Psy\sh());
            try {
                writeFileContent($file, $result['codeFile']);
            } catch (SaveFileException $e) {
                $result['errors'][$file][] = new Message(
                    0,
                    Report::LOG_LEVEL_ERROR,
                    "(Save fix file error)",
                    $e->getMessage()
                );
            }
        }

        return [$file => $result['errors']];
    }, $files);

    return $result;
}
