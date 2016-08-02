<?php

namespace HexletPsrLinter;

use League\CLImate\CLImate;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class PsrLinter
{
    private $cli;

    public function __construct()
    {
        $this->cli = new CLImate();
    }

    public function lint($cmd)
    {
        $errors = [];
        $files = $this->getFiles($cmd['path']);

        foreach ($files as $file) {
            if (file_exists($file) && is_file($file)) {
                $codeFile = file_get_contents($file);
                if ($codeFile !== false) {

                    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
                    $traverser = new NodeTraverser;


                    $visitor = new LinterNodeVisitor(new Rules());

                    // add your visitor
                    $traverser->addVisitor($visitor);

                    try {
                        // parse
                        $stmts = $parser->parse($codeFile);

                        // traverse
                        $traverser->traverse($stmts);

                        if (count($visitor->errors)) {
                            $errors[$file] = $visitor->errors;
                        }

                    } catch (\PhpParser\Error $e) {
                        echo 'Parse Error: ', $e->getMessage();
                    }

                } else {
                    $this->cli->error("Error load file from: {$cmd['path']}");
                }
            } else {
                $this->cli->error("Error path: {$cmd['path']}");;
            }
        }

        return $errors;
    }

    public function getFiles($path)
    {
        $files = [];

        if (is_dir($path)) {
            $rIter = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $path,
                    \RecursiveDirectoryIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($rIter as $item) {
                if ($item->isFile() && $item->getExtension() == "php") {
                    $files[] = $item->getPathName();
                }
            }
        } else {
            $files[] = $path;
        }

        return $files;
    }
}
