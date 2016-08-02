#!/usr/bin/env php

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Commando\Command;
use Colors\Color;
use HexletPsrLinter\PsrLinter;

$color = new Color();

$cmd = new Command();
$cmd->option()
    ->aka('path')
    ->require()
    ->describedAs('File path');
 
if (file_exists($cmd['path'])) {

	$linter = new PsrLinter();
	$result = $linter->lint($cmd);

    if (count($result)) {
        foreach ($result as $file => $errors) {
            echo $color($file . " - " . count($errors) . " errors")->bold().PHP_EOL;
            foreach ($errors as $error) {
                echo $color($error)->red().PHP_EOL;
            }
        }
    }
} else {
    echo $color('ERROR: File or directory on this path can not be found')->bg('red')->bold()->white() . PHP_EOL;
}	
