<?php

namespace hexletPsrLinter;

function checkFunctionName($name)
{
    $regex = "^[a-z]+([A-Z]?[a-z]+)+$";
    $result = preg_match_all("/$regex/", $name);
    if ($result == 0) {
        return false;
    } else {
        return true;
    }
}
