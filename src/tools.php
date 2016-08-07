<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Exceptions\LoadFileException;
use HexletPsrLinter\Exceptions\FileExistsException;
use HexletPsrLinter\Report\Message;
use HexletPsrLinter\Report\Report;
use League\CLImate\CLImate;

function getFilesPath($path)
{
    if (!file_exists($path)) {
        throw new FileExistsException("File or directory on this path can not be found: {$path}");
    }
    $files = [];

    if (is_dir($path)) {
        $rIter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($rIter as $item) {
            if ($item->isFile() && $item->getExtension() == "php") {
                $files[] = $item->getPathName();
            }
        }

        return $files;
    }

    $files[] = $path;
    return $files;
}

function getFileContent($path)
{
    if (!file_exists($path) || !is_file($path)) {
        throw new FileExistsException("File not found at the path: {$path}");
    }

    $code = file_get_contents($path);
    if ($code === false) {
        throw new LoadFileException("Error load file from: {$path}");
    }

    return $code;
}

/**
 * @param $logs mixed
 * print report to console
 */
function printCli($logs)
{
    $cli = new CLImate();
    
    foreach ($logs as $file => $messages) {
        $cli->lightBlue()->bold()->inline($file)->br();
        /** @var $message Message */
        foreach ($messages as $message) {
            $cli->white()->bold()->inline(sprintf('%-5s', $message->getLine()));

            $format = '%-10s';
            $text = $message->getLevel();
            switch ($text) {
                case Report::LOG_LEVEL_ERROR:
                    $cli->red()->inline(sprintf($format, $text));
                    break;
                case Report::LOG_LEVEL_WARNING:
                    $cli->yellow()->inline(sprintf($format, $text));
                    break;
                case Report::LOG_LEVEL_FIXED:
                    $cli->green()->inline(sprintf($format, $text));
                    break;
            }

            $cli->lightCyan()->bold()->inline(sprintf('%-25s', $message->getName()));
            $cli->white()->inline($message->getMessage())->br();
        }
    }
    $cli->br();
}
