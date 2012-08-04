<?php

function __autoload ($classname)
{
	require_once ("./" . strtolower($classname) . ".class.php");
}

require_once ("errordispatcher.php");
require_once ("filenames.config.php");

?>
