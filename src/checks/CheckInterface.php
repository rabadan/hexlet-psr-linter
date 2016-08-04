<?php

namespace HexletPsrLinter\checks;

use PhpParser\Node;

interface CheckInterface
{
    public function isCheck(Node $node);
    public function validate(Node $node);
    public function getErrors();
}
