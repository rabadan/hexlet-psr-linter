<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testMessage()
    {
        $line = 1;
        $level = Report::LOG_LEVEL_WARNING;
        $name = 'test';
        $text = 'TEXT';

        $message = new Message($line, $level, $name, $text);

        $this->assertEquals($message->getLine(), $line);
        $this->assertEquals($message->getLevel(), $level);
        $this->assertEquals($message->getName(), $name);
        $this->assertEquals($message->getMessage(), $text);
    }
}
