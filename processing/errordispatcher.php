<?php

function errorDispatcher ($error, $additional = "")
{
	$error_string;
	switch ($error)
	{
		case 1:
			$error_string = "IP alias in file <bb-rules> not found in file <bb-ipaliases>";
		break;
		case 2:
			$error_string = "Too few elements in file <bb-rules>";
		break;
		case 3:
			$error_string = "Invalid protocol specified";
		break;
		case 4:
			$error_string = "Invalid connection originate specified";
		break;
#		case :
#			$error_string = "";
#		break;
	}

	$erroronline = "";
	if (!empty($additional)) { $erroronline = " on line " . $additional; }
	echo "\nERROR" . $erroronline . ": " . $error_string . "\n";
	die ();
}

?>
