<?php

echo "Сorrect function names";


function Name()
{
    return "Bad!";
}

$water = "Всякая вода, чтоб в файле было еще что то, кроме функций";

function NameGood()
{
    return "Sumptuously!";
}

function bad_bad_name($water)
{
    return $water;
}
