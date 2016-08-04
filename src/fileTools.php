<?php

namespace HexletPsrLinter;

use HexletPsrLinter\Exceptions\LoadFileException;
use HexletPsrLinter\Exceptions\FileExistsException;

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
