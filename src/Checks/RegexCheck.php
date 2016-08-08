<?php

namespace HexletPsrLinter\Checks;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class RegexCheck implements CheckInterface
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
            $this->errors[] = new Message(
                $node->getLine(),
                Report::LOG_LEVEL_ERROR,
                $node->name,
                $this->comment
            );
            return false;
        }
        return true;
    }

    public function modification(Node $node)
    {
        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
