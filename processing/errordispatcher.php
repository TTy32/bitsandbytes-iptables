<?php

function errorDispatcher ($error)
{
	$error_string;
	switch ($error)
	{
		case 1:
			$error_string = "IP alias in file <bb-rules> not found in file <bb-ipaliases>";
		break;
		case 2:
			$error_string = "Too few elements in file <bb-rules>";
	}

	echo "ERROR: " . $error_string;
	die ();
}

?>
