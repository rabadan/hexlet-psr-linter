<?php

namespace TestLinter;

class TestClass
{
    public $camel_case;

    public function iniSet()
    {
        // побочный эффект: изменение настроек
        ini_set('error_reporting', E_ALL);
        return 1;
    }

    public function camel_CAse()
    {
        echo "\n";
        return 1;
    }

    public function __constructor()
    {
        return 1;
    }

    public function __destruction()
    {
        return 1;
    }
}
