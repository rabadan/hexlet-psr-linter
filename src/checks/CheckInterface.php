<?php

namespace HexletPsrLinter\checks;

interface CheckInterface
{
    public function isCheck($node);
    public function validate($node);
    public function getErrors();
}
