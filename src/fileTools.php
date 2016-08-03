<?php

namespace HexletPsrLinter;

function getFilesPath($path)
{
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
    } else {
        $files[] = $path;
    }

    return $files;
}

function getFile($path)
{
    $result = [
        'code'   => null,
        'errors' => [],
    ];

    if (!file_exists($path) || !is_file($path)) {
        $result['errors'] = "File not found at the path: {$path}";
        return $result;
    }

    $result['code'] = file_get_contents($path);
    if ($result['code'] === false) {
        $result['errors'] = "Error load file from: {$path}";
    }

    return $result;
}
