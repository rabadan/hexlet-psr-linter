<?php

namespace HexletPsrLinter\Linter;

use function HexletPsrLinter\getFilesPath;
use function HexletPsrLinter\getFileContent;

/**
 * Class FileLinter
 * @package HexletPsrLinter
 */

class FileLinter
{
    private $linter;

    public function __construct(Linter $linter)
    {
        $this->linter = $linter;
    }

    public function lint($path)
    {
        $files = getFilesPath($path);

        $result = array_map(function ($file) {
            return [$file => $this->linter->lint(getFileContent($file))];
        }, $files);

        return $result;
    }
}
