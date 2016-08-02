<?php

namespace HexletPsrLinter;
/**
 * Class Rules
 */
class Rules
{
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

    public function validateFunctionName($name)
    {
        if (in_array($name, $this->magicMethod)) {
            return true;
        }
        return \PHP_CodeSniffer::isCamelCaps($name, false, true, true);
    }
}
