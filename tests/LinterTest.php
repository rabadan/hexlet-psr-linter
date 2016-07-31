<?php

namespace Linter;

class LinterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testTest()
    {
        $linter = new Linter();
        $this->assertEquals("test", $linter->test());
    }
}
