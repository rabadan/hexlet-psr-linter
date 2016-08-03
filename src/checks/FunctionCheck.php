<?php

namespace HexletPsrLinter\checks;

class FunctionCheck implements CheckInterface
{
    private $errors = [];

    public function isCheck($node)
    {
        return $node->getType() === 'Stmt_Function';
    }

    public function validate($node)
    {
        if (!\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)) {
            $this->errors = [
                $node->getLine(),
                $node->name
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
