#!/usr/bin/env php

<?php

namespace PsrLinter;

require_once __DIR__ . '/../vendor/autoload.php';

$liner = new PsrLinter();

echo $liner->test();