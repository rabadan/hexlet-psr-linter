<?php

namespace HexletPsrLinter\Checks;

use PhpParser\Node;

abstract class AbstractCheck
{
    abstract public function isAcceptable(Node $node);
    abstract public function validate(Node $node);
    abstract public function getErrors();

    public function modification(Node $node)
    {
        return false;
    }
}
