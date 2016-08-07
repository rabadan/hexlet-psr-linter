<?php

namespace HexletPsrLinter\Visitor;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class SideEffectsVisitor extends NodeVisitorAbstract
{
    private $sideEffects;
    private $structure;

    /**
     * @param Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        if (!($node instanceof Stmt\Namespace_)) {
            $this->sideEffects = $this->isSideEffect($node) || $this->sideEffects;
            $this->structure = $this->isStructure($node) || $this->structure;
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
    }

    public function isSideEffect(Node $node)
    {
        $sideEffectTypes = [
            'Expr_FuncCall',
            'Expr_Include',
            'Stmt_Echo'
        ];

        return in_array($node->getType(), $sideEffectTypes);
    }


    public function isStructure(Node $node)
    {
        $classNodeStmt = Node\Stmt::class;
        if (($node instanceof $classNodeStmt) && ($node->getType() !== "Stmt_Echo")) {
            return true;
        }

        return false;
    }

    public function isValid()
    {
        return !($this->structure && $this->sideEffects);
    }
}
