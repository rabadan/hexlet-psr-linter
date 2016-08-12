<?php

namespace OthersPsrLinter\Checks;

use HexletPsrLinter\Checks\AbstractCheck;
use HexletPsrLinter\Checks\CheckInterface;
use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class VariableLengthCheck extends AbstractCheck implements CheckInterface
{
    private $errors = [];
    private $nodeType;
    private $comment;

    public function __construct(
        $nodeType = 'Expr_Variable',
        $comment = "The length of the variable is greater than 10 characters"
    ) {
        $this->nodeType = $nodeType;
        $this->comment  = $comment;
    }


    public function isAcceptable(Node $node)
    {
        return $node->getType() === $this->nodeType;
    }

    public function validate(Node $node)
    {
        if (mb_strlen($node->name) > 10) {
            $this->errors[] = [
                'line'      => $node->getLine(),
                'logLevel'  => Report::LOG_LEVEL_INFO,
                'name'      => $node->name,
                'message'   => $this->comment
            ];

            return false;
        }
        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
