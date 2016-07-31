#!/usr/bin/env php

<?php

namespace Linter;

require_once __DIR__ . '/../vendor/autoload.php';

$liner = new Linter();

echo $liner->test();

