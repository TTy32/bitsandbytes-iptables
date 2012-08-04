#!/usr/bin/env php
<?php

require_once ("global.include.php");

// Ruleset for generated rules
$ruleset = new ruleset();

$file_bbrules = file ($filename["bb-rules"]);
foreach ($file_bbrules as $key => $value) // Loop rules (lines)
{

	
	if (substr(trim($value), 0, 1) != '#' && ctype_space($value) == FALSE) // Check for comment line
	{
		$bbrules_line = new parseLine($value);
		$bbrules_line_elements = $bbrules_line->getElements();
	
	if (empty($bbrules_line_elements[4])) { errorDispatcher(2); }
		
		$bbrules_line_element_description = $bbrules_line_elements[0];
		$bbrules_line_element_direction = $bbrules_line_elements[1];
		$bbrules_line_element_ipalias = $bbrules_line_elements[2];
		$bbrules_line_element_protocol = $bbrules_line_elements[3];
		$bbrules_line_element_ports = $bbrules_line_elements[4];
		
		// Add descriptive comments
		$ruleset->addRule( "" );
		$ruleset->addRule( "# " . $bbrules_line_element_description );

		$bbrules_line_element_ports_explodebycomma = explode (",", $bbrules_line_element_ports);
		$bbrules_line_element_ipaddr;

		$file_bbipaliases = file ($filename["bb-ipaliases"]);
		foreach ($file_bbipaliases as $key2 => $value2)
		{
			$bbipaliases_line = new parseLine($value2);
			$bbipaliases_line_elements = $bbipaliases_line->getElements();
			if ($bbipaliases_line_elements[0] == $bbrules_line_element_ipalias)
			{
				$bbrules_line_element_ipaddr = $bbipaliases_line_elements[1];
			}
		}
		if (empty($bbrules_line_element_ipaddr)) { errorDispatcher(1); }

		// Loop through ports (comma delimiter)
		foreach ($bbrules_line_element_ports_explodebycomma as $key => $value)
		{
			$bbrules_line_element_ports_explodebydash = explode ("-", $value);
			$individual_port;
			if (empty($bbrules_line_element_ports_explodebydash[1])) // Make sure to iterate the for loop once in case of an individual port
			{
				$bbrules_line_element_ports_explodebydash[1] = $bbrules_line_element_ports_explodebydash[0];
			}
			for ($individual_port = $bbrules_line_element_ports_explodebydash[0]; $individual_port <= $bbrules_line_element_ports_explodebydash[1]; $individual_port++) // Loop through range of ports. In case of a single port the for loop is iterated once.
			{
				$individual_port = trim($individual_port); // Whitespace fix
				switch ($bbrules_line_element_direction)
				{
					
					case "INOUT":
						switch ($bbrules_line_element_protocol)
						{
							case "BOTH":
								$rule = new rule("IN", "UDP", $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("IN", "TCP", $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("OUT", "UDP", $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("OUT", "TCP", $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
							default:
								$rule = new rule("IN", $bbrules_line_element_protocol, $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("OUT", $bbrules_line_element_protocol, $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
						}
					break;

					default:
						switch ($bbrules_line_element_protocol)
						{
							case "BOTH":
								$rule = new rule($bbrules_line_element_direction, "UDP", $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule($bbrules_line_element_direction, "TCP", $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
							default:
								$rule = new rule($bbrules_line_element_direction, $bbrules_line_element_protocol, $bbrules_line_element_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
						}
					break;
				}

			} // End of port range or individual port
		} // End of port loop
	} // End of check loop
} // End of file loop

// Add descriptive comments
$ruleset->addRule( "" );
$ruleset->addRule( "# Blocklist" );

$file_bbblocklist = file ($filename["bb-blocklist"]);
foreach ($file_bbblocklist as $key => $value) // Loop ip entries (lines)
{
	if (strpos($value, "#") != 1) // Check for comment line
	{
		$ruleset->addRule ( "iptables -A INPUT -s " . trim($value) . " -j DROP" );
	}
}

file_put_contents($filename["bb-iptables_generated"], $ruleset->getRuleset() );

