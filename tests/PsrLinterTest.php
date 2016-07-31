<?php

namespace PsrLinter;

class PsrLinterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testTest()
    {
        $linter = new PsrLinter();
        $this->assertEquals("test", $linter->test());
    }
}
