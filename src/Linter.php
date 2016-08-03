<?php

namespace HexletPsrLinter;

use HexletPsrLinter\checks\FunctionCheck;
use HexletPsrLinter\checks\MethodCheck;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class Linter
{

    public function run($cmd)
    {
        $files = getFilesPath($cmd['path']);
        $errors = [];

        foreach ($files as $file) {
            $errors[$file] = $this->lint(getFile($file));
        }

        return $errors;
    }


    public function lint($codeFile)
    {
        $error = [];

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $visitor = new NodeVisitor([
            new MethodCheck,
            new FunctionCheck
        ]);
        $traverser->addVisitor($visitor);

        try {
            // parse
            $stmts = $parser->parse($codeFile);

            // traverse
            $traverser->traverse($stmts);

            if (!empty($visitor->getErrors())) {
                $error = $visitor->getErrors();
            }
        } catch (\PhpParser\Error $e) {
            $error = "Parse Error: ". $e->getMessage();
        }

        return $error;
    }
}
