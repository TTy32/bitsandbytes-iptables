#!/usr/bin/env php
<?php

class ruleset
{
	private $rules = Array();
	
	public function addRule($rule)
	{
		array_push ($this->rules, $rule . "\n");
	}
	public function getRuleset()
	{
		return $this->rules;
	}
}

class rule
{
	private $rule_prefix = "iptables";
	private $rule_direction; 				// Set by constructor
	private $rule_direction_prefix = "-A";
	private $rule_protocol; 				// Set by constructor
	private $rule_protocol_prefix = "-p";
	private $rule_ipaddr; 					// Set by constructor
	private $rule_ipaddr_prefix; 			//Set by getRule
	private $rule_port; 					// Set by constructor
	private $rule_port_prefix; 				//Set by getRule
	private $rule_suffix = "-m state --state NEW,ESTABLISHED -j ACCEPT";

	private $rule;
	function __construct($direction, $protocol, $ipaddr, $port)
	{
		if ($direction == "IN") { $direction = "INPUT"; }
		if ($direction == "OUT") { $direction = "OUTPUT"; }
		$this->rule_direction = $direction;
		$this->rule_protocol = $protocol;
		$this->rule_ipaddr = $ipaddr;
		$this->rule_port = $port;
	}
	
	public function getRule()
	{
		switch ($this->rule_direction)
		{
			case "INPUT":
				$this->rule_ipaddr_prefix = "-d";
				$this->rule_port_prefix = "--dport";
			break;
			case "OUTPUT":
				$this->rule_ipaddr_prefix = "-s";
				$this->rule_port_prefix = "--sport";
			break;
		}
		$this->rule = 	$this->rule_prefix . " " . 
						$this->rule_direction_prefix . " " . $this->rule_direction . " " . 
						$this->rule_protocol_prefix . " " . $this->rule_protocol . " " . 
						$this->rule_ipaddr_prefix . " " . $this->rule_ipaddr . " " . 
						$this->rule_port_prefix . " " . $this->rule_port . " " . 
						$this->rule_suffix;
		return $this->rule;
	}
}

$ruleset = new ruleset();

$lines = file ("bb-rules");
foreach ($lines as $key => $value) // Loop rules (lines)
{
	if (strpos($value, "#") != 1) // Check for comment line
	{
		$stage1_rules = explode (";", $value); // Split by semicolon

		$stage2_rules_description = $stage1_rules[0];
		$stage2_rules_direction = trim ($stage1_rules[1]);
		$stage2_rules_ipalias = trim ($stage1_rules[2]);
		$stage2_rules_protocol = trim ($stage1_rules[3]);
		$stage2_rules_ports = trim ($stage1_rules[4]);
		// TEMPLATE: $stage2_rules_ = trim ($stage1_rules[]);

		$stage2_rules_description = strtoupper ($stage2_rules_description);
		$stage2_rules_direction = strtoupper ($stage2_rules_direction);
		$stage2_rules_ipalias = strtoupper ($stage2_rules_ipalias);
		$stage2_rules_protocol = strtoupper ($stage2_rules_protocol);
		$stage2_rules_ports = strtoupper ($stage2_rules_ports);

		$stage3_rules_ports = explode (",", $stage2_rules_ports);
		$stage3_rules_ipaddr;

		$lines_aliases = file ("bb-ipaliases");
		foreach ($lines_aliases as $key2 => $value2)
		{
			$stage1_alias = explode (";", $value2);
			$stage1_alias[0] = trim ($stage1_alias[0]);
			$stage1_alias[1] = trim ($stage1_alias[1]);
			$stage1_alias[0] = strtoupper ($stage1_alias[0]);
			
			if ($stage1_alias[0] == $stage2_rules_ipalias)
			{
				$stage3_rules_ipaddr = $stage1_alias[1];
			}
		}
		// Loop through ports (comma delimiter)
		foreach ($stage3_rules_ports as $key => $value)
		{
			$stage4_rules_ports = explode ("-", $value);
			$individual_port;
			if (empty($stage4_rules_ports[1]))
			{
				$stage4_rules_ports[1] = $stage4_rules_ports[0];
			}
			for ($individual_port = $stage4_rules_ports[0]; $individual_port <= $stage4_rules_ports[1]; $individual_port++) // Loop through range of ports. In case of a single port the for loop is iterated once.
			{
				switch ($stage2_rules_direction)
				{
					
					case "INOUT":
						switch ($stage2_rules_protocol)
						{
							case "BOTH":
								$rule = new rule("IN", "UDP", $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("IN", "TCP", $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("OUT", "UDP", $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("OUT", "TCP", $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
							default:
								$rule = new rule("IN", $stage2_rules_protocol, $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule("OUT", $stage2_rules_protocol, $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
						}
					break;

					default:
						switch ($stage2_rules_protocol)
						{
							case "BOTH":
								$rule = new rule($stage2_rules_direction, "UDP", $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
								$rule = new rule($stage2_rules_direction, "TCP", $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
							default:
								$rule = new rule($stage2_rules_direction, $stage2_rules_protocol, $stage3_rules_ipaddr, $individual_port);
								$ruleset->addRule( $rule->getRule() );
							break;
						}
					break;
				}

			} // End of port range or individual port
		} // End of port loop
	} // End of check loop
} // End of file loop

file_put_contents("test.rules", $ruleset->getRuleset() );

// ut2004 ; INOUT ; aux ; udp ; 7777,7787,7710-7720, 7771



