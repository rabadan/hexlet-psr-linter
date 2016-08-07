<?php

namespace HexletPsrLinter\Visitor;

use HexletPsrLinter\checks\CheckInterface;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

/**
 * Class NodeVisitor
 */
class NodeVisitor extends NodeVisitorAbstract
{
    private $errors = [];
    private $checks = [];
    private $changeable;

    public function __construct($checks, $changeable)
    {
        $this->changeable = $changeable;
        foreach ($checks as $check) {
            $this->registerCheck($check);
        }
    }

    public function registerCheck(CheckInterface $objectCheck)
    {
        $this->checks[] = $objectCheck;
    }

    /**
     * @param Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        foreach ($this->checks as $check) {
            if ($check->isAcceptable($node)) {
                if (!$check->validate($node, $this->changeable)) {
                    $this->errors[] = $check->getErrors();
                };
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
