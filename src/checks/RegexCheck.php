<?php

namespace HexletPsrLinter\checks;

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

    public function isCheck(Node $node)
    {
        return $node->getType() === $this->nodeType;
    }

    public function validate(Node $node)
    {
        $result = preg_match_all("/{$this->regex}/", $node->name);
        if ($result == 0) {
            $this->errors = [
                $node->getLine(),
                $node->name,
                $this->comment
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
