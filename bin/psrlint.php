#!/usr/bin/env php

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Commando\Command;
$cmd = new Command();
$cmd->option()
    ->aka('path')
    ->require()
    ->describedAs('File path');
 
var_dump($cmd['path']);