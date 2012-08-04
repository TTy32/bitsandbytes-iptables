<?php

class parseLine
{
	private $unprocessed_line;
	private $elements;

	function __construct ($bbrules_line)
	{
		$this->elements = Array();
		$this->unprocessed_line = $bbrules_line;
	}

	public function getElements ()
	{
		$explode_by_semicolon = explode (";", $this->unprocessed_line);
		foreach ($explode_by_semicolon as $key => $value)
		{
			$element_trim = trim ($value);
			$element_uppercase = strtoupper ($element_trim);
			array_push ($this->elements, $element_uppercase);
		}
		return $this->elements;
	}
}

?>
