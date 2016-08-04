<?php

namespace HexletPsrLinter\Checks;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use PhpParser\Node;

class MethodCheck implements CheckInterface
{
    private $errors = [];
    private $nodeType;
    private $regex;
    private $comment;

    public function __construct($nodeType = 'Stmt_ClassMethod', $regex = '^[a-z]+([A-Z]?[a-z]+)+$', $comment = "")
    {
        $this->nodeType = $nodeType;
        $this->regex    = $regex;
        $this->comment  = $comment;
    }

    private $magicMethod = [
        "__construct",
        "__destruct",
        "__call",
        "__callStatic",
        "__get",
        "__set",
        "__isset",
        "__unset",
        "__sleep",
        "__wakeup",
        "__toString",
        "__invoke",
        "__set_state",
        "__clone",
        "__debugInfo"
    ];

    public function validate(Node $node)
    {
        if (!in_array($node->name, $this->magicMethod)) {
            $result = preg_match_all("/{$this->regex}/", $node->name);
            if ($result == 0) {
                $this->errors = new Message(
                    $node->getLine(),
                    Report::LOG_LEVEL_ERROR,
                    $node->name,
                    $this->comment
                );

                return false;
            }
        }
        return true;
    }

    public function isAcceptable(Node $node)
    {
        return $node->getType() === $this->nodeType;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
