<?php

namespace HexletPsrLinter\checks;

class MethodCheck implements CheckInterface
{
    private $errors = [];
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

    public function isCheck($node)
    {
        return $node->getType() === 'Stmt_ClassMethod';
    }

    public function validate($node)
    {
        if (!in_array($node->name, $this->magicMethod) &&
            !\PHP_CodeSniffer::isCamelCaps($node->name, false, true, true)) {
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
