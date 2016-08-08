<?php
namespace TestLinter;

class TestClass
{
    public function isCase()
    {
        return 'test';
    }
    public function camel()
    {
        return $this->isCase();
    }
    public function __construct()
    {
        return 1;
    }
    public function __destruct()
    {
        return 1;
    }
}
