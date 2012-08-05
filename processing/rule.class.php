<?php

class rule
{
	private $rule_prefix = "iptables";		// Default
	private $rule_chain; 					// Set by constructor
	private $rule_chain_prefix = "-A";		// Default
	private $rule_protocol; 				// Set by constructor
	private $rule_protocol_prefix = "-p";	// Default
	private $rule_ipaddr; 					// Set by constructor
	private $rule_ipaddr_prefix; 			// Set by constructor
	private $rule_port; 					// Set by constructor
	private $rule_port_prefix; 				// Set by constructor
	private $rule_state = "NEW,ESTABLISHED";// May be set by constructor
	private $rule_extra;					// May be set by constructor
	private $rule_state_prefix = "-m state --state"; // Default
	private $rule_suffix = "-j ACCEPT";		// Default

	private $rule;
	function __construct($chain, $protocol, $ipaddr_prefix, $ipaddr, $port_prefix, $port, $state, $extra)
	{
		$this->rule_chain = $chain;
		$this->rule_protocol = $protocol;
		$this->rule_ipaddr_prefix = $ipaddr_prefix;
		$this->rule_ipaddr = $ipaddr;
		$this->rule_port_prefix = $port_prefix;
		$this->rule_port = $port;
		if (!empty($state)) { $this->rule_state = $state; }
		if (!empty($extra)) { $this->rule_extra = $extra; }
	}
	
	public function getRule()
	{
		$this->rule = 	$this->rule_prefix . " " . 
						$this->rule_chain_prefix . " " . $this->rule_chain . " " . 
						$this->rule_protocol_prefix . " " . $this->rule_protocol . " " . 
						$this->rule_ipaddr_prefix . " " . $this->rule_ipaddr . " " . 
						$this->rule_port_prefix . " " . $this->rule_port . " " . 
						$this->rule_state_prefix . " " . $this->rule_state . " " .
						$this->rule_extra . " " .
						$this->rule_suffix;
		return $this->rule;
	}
}

?>
