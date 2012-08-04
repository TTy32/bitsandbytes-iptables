<?php

class ruleset
{
	private $rules = Array();
	
	public function addRule($rule)
	{
		array_push ($this->rules, $rule . "\n");
		echo $rule . "\n";
	}
	public function getRuleset()
	{
		return $this->rules;
	}
}

?>
