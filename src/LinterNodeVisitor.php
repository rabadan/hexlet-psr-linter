<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt;

class LinterNodeVisitor extends NodeVisitorAbstract
{
    public $errors;
    private $rules;

    public function __construct($rules)
    {
        $this->rules = $rules;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Stmt\Function_) {
            if (!$this->rules->validateFunctionName($node->name)) {
                $this->errors[]= "{$node->name} - not valid name";
            };
        }
        
        if ($node instanceof Stmt\ClassMethod) {
            if (!$this->rules->validateFunctionName($node->name)) {
                $this->errors[]= "{$node->name} - not valid name";
            };
        }
    }
}
