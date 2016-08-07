<?php

// побочный эффект: изменение настроек
ini_set('error_reporting', E_ALL);

// побочный эффект: передача данных в выходной поток
echo "\n";

function foo()
{
    // тело функции
    return 'rrr';
}

// побочный эффект: подключение файла
include "bad.php";

$r = foo();

