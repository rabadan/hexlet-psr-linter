<?php

namespace HexletPsrLinter\Visitor;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

/**
 * Class NodeVisitor
 */
class GetChecksVisitor extends NodeVisitorAbstract
{
    private $namespace;
    private $className;

    /**
     * @param Node $node
     * @return void
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node->name;
        }

        if ($node instanceof Node\Stmt\Class_ && $node->type !== Node\Stmt\Class_::MODIFIER_ABSTRACT) {
            $this->className = $node->name;
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
    }

    public function isClassExists()
    {
        return !is_null($this->namespace) && !is_null($this->className);
    }

    public function getFullClassName()
    {
        return "{$this->namespace}\\{$this->className}";
    }
}
