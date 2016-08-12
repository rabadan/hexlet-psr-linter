<?php

namespace HexletPsrLinter\Visitor;

use HexletPsrLinter\Checks\CheckInterface;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Class NodeVisitor
 */
class NodeVisitor extends NodeVisitorAbstract
{
    private $checks = [];
    private $dataHaveChanged;
    private $modifyData;

    public function __construct($checks, $modifyData)
    {
        $this->modifyData = $modifyData;
        $this->dataHaveChanged = false;
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
                $passed = $check->validate($node);

                if (!$passed && $this->modifyData) {
                    $this->dataHaveChanged = $check->modification($node) || $this->dataHaveChanged;
                };
            }
        }
    }

    public function getErrors()
    {
        $allErrorsCheck = [];
        foreach ($this->checks as $check) {
            $errorsCheck = $check->getErrors();
            if (!empty($errorsCheck)) {
                $allErrorsCheck = array_merge($allErrorsCheck, $errorsCheck);
            }
        }
        return $allErrorsCheck;
    }

    public function isDataHaveChanged()
    {
        return $this->dataHaveChanged;
    }
}
