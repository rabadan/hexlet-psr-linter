<?php 

echo "Test file";


function oneGood()
{
	return "One good!";
}


function OneBad()
{
	return "One bad!";
}

$x2 = function($val) {
 return $val * 2;
};

$val = $x2(4);
