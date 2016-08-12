<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Checks\CheckInterface;
use HexletPsrLinter\Checks\MethodCheck;
use HexletPsrLinter\Checks\RegexCheck;
use HexletPsrLinter\Checks\SideEffectsCheck;
use HexletPsrLinter\Checks\VariableCheck;
use HexletPsrLinter\Exceptions\SaveFileException;
use HexletPsrLinter\Report\Report;
use HexletPsrLinter\Visitor\GetChecksVisitor;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use HexletPsrLinter\Visitor\NodeVisitor;

function makeLinter()
{
    return function ($codeFile, $params) {
        $modifyData = isset($params['fix'])?$params['fix']:false;
        $includeChecks = isset($params['includeChecks'])?$params['includeChecks']:[];
        $resultLint = [];
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;

        $checksList = array_merge([
            new MethodCheck(),
            new RegexCheck('Stmt_Function', '^[a-z]+([A-Z]?[a-z]+)*$', 'No camel case function name'),
            new VariableCheck(),
            new SideEffectsCheck(),
        ], $includeChecks);

        $visitor = new NodeVisitor($checksList, $modifyData);

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
            $resultLint[] = [
                'line'      => 0,
                'logLevel'  => Report::LOG_LEVEL_ERROR,
                'name'      => "(Parse Error)",
                'message'   => $e->getMessage()
            ];
        }
        finally {
            return ["result" => $resultLint, "codeFile" => $codeFile];
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
                $resultLint['result'][$file][] = [
                    'line'      => 0,
                    'logLevel'  => Report::LOG_LEVEL_ERROR,
                    'name'      => "(Save fix file error)",
                    'message'   => $e->getMessage()
                ];
            }
        }

        return [$file => $resultLint['result']];
    }, $files);

    return $resultPathLint;
}


function getCheckers($path)
{
    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    $resultReceiptChecks = array_map(function ($file) use ($parser) {
        $result = [
            'file'      => $file,
            'status'    => false,
            'class'     => null,
            'log'       => [
                'line'      => 0,
                'logLevel'  => Report::LOG_LEVEL_ERROR,
                'name'      => '(Get Checker)',
                'message'   => ""
            ]
        ];
        $getChecksVisitor = new GetChecksVisitor();
        $traverser = new NodeTraverser();
        $traverser->addVisitor($getChecksVisitor);
        $codeFile = getFileContent($file);
        $stmts = $parser->parse($codeFile);
        $traverser->traverse($stmts);

        if ($getChecksVisitor->isClassExists()) {
            $loadClassName = $getChecksVisitor->getFullClassName();
            include_once($file);
            $result['class'] = $loadClassName;
            if (class_exists($loadClassName)) {
                if (in_array(CheckInterface::class, class_implements($loadClassName))) {
                    $result['status'] = true;
                    $result['log']['logLevel'] = Report::LOG_LEVEL_INFO;
                    $result['log']['message'] = "Good load";
                } else {
                    $result['log']['message'] = "Class does not match interface";
                }
            } else {
                $result['log']['message'] = "Class not found";
            }
        } else {
            $result['log']['message'] = "The file does not contain defenition class";
        }
        return $result;
    }, getFilesPath($path));

    return $resultReceiptChecks;
}
