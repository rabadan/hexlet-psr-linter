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
	$linter->lint($cmd);

	/*foreach ($linter->chek($cmd) as $path => $result) {
		echo $color($path)->bold() . PHP_EOL;
		echo $color("     Amount of functions: ");
		echo $color($result['count'])->bold() . PHP_EOL;
		echo $color("  Correct names function: ");
		echo $color($result['correctly'])->bold()->green() . PHP_EOL;
		echo $color("    Wrong names function: ");
		echo (($result['wrong']>0)?$color($result['wrong'])->bold()->red():$color($result['wrong'])->bold()) . PHP_EOL;

 

	 	//echo print_r($result,1);
	 	echo PHP_EOL;
	} */

} else {
    echo $color('ERROR: File or directory on this path can not be found')->bg('red')->bold()->white() . PHP_EOL;
}	
