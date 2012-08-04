<?php

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
		if ($direction == "IN") { $direction = "INPUT "; }
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
			case "INPUT ":
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

?>
