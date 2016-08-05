<?php

namespace HexletPsrLinter\Report;

use League\CLImate\CLImate;
use League\CLImate\Util\Reader\ReaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @property $cli CLImate
*/

class Report implements ReportInterface
{
    const LOG_LEVEL_ERROR = 'error';
    const LOG_LEVEL_WARNING = 'warning';
    const LOG_LEVEL_FIXED = 'fixed';

    private $logs;
    private $cli;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $this->logs = [];
        $this->cli = new CLImate();
    }

    /**
     * @param $file string
     * @param $message Message
     */
    public function addLog($file, $message)
    {
        $this->logs[$file][] = $message;
    }

    /**
     * @param $file string
     * @param $messages []
     */
    public function addLogs($file, $messages)
    {
        foreach ($messages as $message) {
            $this->logs[$file][] = $message;
        }
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function isEmpty()
    {
        return empty($this->logs);
    }

    /**
     * print text report to console
     */
    public function createReport()
    {
        foreach ($this->getLogs() as $file => $messages) {
            $this->cli->lightBlue()->bold()->inline($file)->br();
            /** @var $message Message */
            foreach ($messages as $message) {
                $this->cli->white()->bold()->inline(sprintf('%-5s', $message->getLine()));

                $format = '%-10s';
                $text = $message->getLevel();
                switch ($text) {
                    case self::LOG_LEVEL_ERROR:
                        $this->cli->red()->inline(sprintf($format, $text));
                        break;
                    case self::LOG_LEVEL_WARNING:
                        $this->cli->yellow()->inline(sprintf($format, $text));
                        break;
                    case self::LOG_LEVEL_FIXED:
                        $this->cli->green()->inline(sprintf($format, $text));
                        break;
                }

                $this->cli->lightCyan()->bold()->inline(sprintf('%-25s', $message->getName()));
                $this->cli->white()->inline($message->getMessage())->br();
            }
        }
    }
}
