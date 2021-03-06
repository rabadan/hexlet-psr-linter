<?php

namespace HexletPsrLinter\Checks;

use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class RegexCheck extends AbstractCheck implements CheckInterface
{
    private $errors = [];
    private $nodeType;
    private $regex;
    private $comment;

    public function __construct($nodeType, $regex, $comment = "")
    {
        $this->nodeType = $nodeType;
        $this->regex    = $regex;
        $this->comment  = $comment;
    }

    public function isAcceptable(Node $node)
    {
        return $node->getType() === $this->nodeType;
    }

    public function validate(Node $node)
    {
        $result = preg_match_all("/{$this->regex}/", $node->name);
        if ($result == 0) {
            $this->errors[] = [
                'line'      => $node->getLine(),
                'logLevel'  => Report::LOG_LEVEL_ERROR,
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
