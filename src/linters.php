<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\FixVariableCheck;
use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Checks\SideEffectsCheck;
use HexletPsrLinter\Exceptions\FileExistsException;
use HexletPsrLinter\Exceptions\SaveFileException;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use HexletPsrLinter\Visitor\NodeVisitor;

function linter()
{
    return function ($codeFile, $fix = false, $filePath = null) {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new NodeVisitor([
            new MethodCheck(),
            new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)*$', 'No camel case function name'),
            new RegexCheck('Expr_Variable', '^[a-z]+([A-Z]?[a-z1-9]+)*$', 'No camel case Variable name'),
            new SideEffectsCheck(),
            new FixVariableCheck($fix)
        ]);

        $traverser->addVisitor($visitor);

        try {
            $stmts = $parser->parse($codeFile);
            $traverser->traverse($stmts);
            $errors = $visitor->getErrors();

            if ($fix && !empty($errors)) {
                $fixItems = array_filter($errors, function ($item) {
                    return $item->getLevel() == Report::LOG_LEVEL_FIXED;
                });

                if (!empty($fixItems)) {
                    $prettyPrinter = new PrettyPrinter\Standard();
                    writeFileContent($filePath, "<?php" . PHP_EOL . $prettyPrinter->prettyPrint($stmts) . PHP_EOL);
                }
            }
        } catch (\PhpParser\Error $e) {
            $errors[] = new Message(0, Report::LOG_LEVEL_ERROR, "(Parse Error)", $e->getMessage());
        } catch (SaveFileException $e) {
            $errors[] = new Message(0, Report::LOG_LEVEL_ERROR, "(Save fix file error)", $e->getMessage());
        }
        finally {
            return $errors;
        }
    };
}

function fileLinter($linter, $path, $fix = false)
{
    $files = getFilesPath($path);

    $result = array_map(function ($file) use ($linter, $fix) {
        return [$file => $linter(getFileContent($file), $fix, $file)];
    }, $files);

    return $result;
}
