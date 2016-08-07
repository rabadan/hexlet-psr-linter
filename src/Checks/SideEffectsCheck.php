<?php

namespace HexletPsrLinter\Checks;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class SideEffectsCheck implements CheckInterface
{
    private $errors = [];
    private $endLineChecks;
    private $comment;
    private $nodeStmtTypes;
    private $sideEffectTypes;

    private $isStmt;
    private $isSideEffect;


    public function __construct($comment = "")
    {
        $this->comment = $comment;
        $this->nodeStmtTypes[] = 'Stmt_Function';
        $this->nodeStmtTypes[] = 'Stmt_Class';

        $this->sideEffectTypes[] = 'Expr_FuncCall';
        $this->sideEffectTypes[] = 'Expr_Include';
        $this->sideEffectTypes[] = 'Stmt_Echo';

        $this->reset();
    }

    public function isAcceptable(Node $node)
    {
        if ($this->isSideEffects()) {
            return false;
        }

        if ($this->endLineChecks <= $node->getAttribute('endLine')) {
            $this->endLineChecks = $node->getAttribute('endLine');
            return true;
        }

        return false;
    }

    public function validate(Node $node)
    {
        if (in_array($node->getType(), $this->sideEffectTypes)) {
            $this->isSideEffect = true;
        }

        if (in_array($node->getType(), $this->nodeStmtTypes)) {
            $this->isStmt = true;
        }

        if ($this->isSideEffects()) {
            $this->errors = new Message(
                0,
                Report::LOG_LEVEL_ERROR,
                "",
                PHP_EOL . "A file should declare new symbols (classes, functions, constants, etc.) " . PHP_EOL .
                "and cause no other side effects, or it should execute logic with side effects, " . PHP_EOL .
                "but should not do both. "
            );
            return false;
        }

        return true;
    }

    public function isSideEffects()
    {
        return $this->isSideEffect && $this->isStmt;
    }

    public function reset()
    {
        $this->endLineChecks = 0;
        $this->isStmt = false;
        $this->isSideEffect = false;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
