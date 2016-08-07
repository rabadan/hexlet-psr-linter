<?php

namespace HexletPsrLinter\Checks;

use PhpParser\Node;

interface CheckInterface
{
    public function isAcceptable(Node $node);
    public function validate(Node $node, $changeable);
    public function getErrors();
}
