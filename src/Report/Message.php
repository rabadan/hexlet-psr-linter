<?php

namespace HexletPsrLinter\Report;

use phpDocumentor\Reflection\Types\Integer;

class Message
{
    private $line;
    private $level;
    private $name;
    private $message;

    /**
     * Message constructor.
     * @param $line
     * @param $level
     * @param $name
     * @param $message
     */
    public function __construct($line, $level, $name, $message)
    {
        $this->line = $line;
        $this->level = $level;
        $this->name = $name;
        $this->message = $message;
    }

    /**
     * @return Integer
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return Integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
