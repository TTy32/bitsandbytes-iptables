#!/usr/bin/env php
<?php

$default1 = "-m state --state NEW,ESTABLISHED -j ACCEPT\n";

$output_lines = Array();
$ipaddr;

$lines = file ("rules");
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

		$lines_aliases = file ("bb-aliases");
		foreach ($lines_aliases as $key2 => $value2)
		{
			$stage1_alias = explode (";", $value2);
			$stage1_alias[0] = trim ($stage1_alias[0]);
			$stage1_alias[1] = trim ($stage1_alias[1]);
			$stage1_alias[0] = strtoupper ($stage1_alias[0]);
			
			if ($stage1_alias[0] == $stage2_rules_ipalias)
			{
				$ipaddr = $stage1_alias[1];
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
					case "IN":
						switch ($stage2_rules_protocol)
						{
							case "UDP":
								array_push ($output_lines, "iptables -A INPUT  -p UDP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
							break;
							case "TCP":
								array_push ($output_lines, "iptables -A INPUT  -p TCP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
							break;
							case "BOTH":
								array_push ($output_lines, "iptables -A INPUT  -p UDP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A INPUT  -p TCP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
							break;
						}
					break;
					case "OUT":
						switch ($stage2_rules_protocol)
						{
							case "UDP":
								array_push ($output_lines, "iptables -A OUTPUT -p UDP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
							break;
							case "TCP":
								array_push ($output_lines, "iptables -A OUTPUT -p TCP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
							break;
							case "BOTH":
								array_push ($output_lines, "iptables -A OUTPUT -p UDP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A OUTPUT -p TCP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
							break;
						}
					break;
					case "INOUT":
						switch ($stage2_rules_protocol)
						{
							case "UDP":
								array_push ($output_lines, "iptables -A OUTPUT -p UDP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A INPUT  -p UDP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
							break;
							case "TCP":
								array_push ($output_lines, "iptables -A OUTPUT -p TCP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A INPUT  -p TCP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
							break;
							case "BOTH":
								array_push ($output_lines, "iptables -A OUTPUT -p UDP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A OUTPUT -p TCP -s " . $ipaddr . " --sport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A INPUT  -p UDP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
								array_push ($output_lines, "iptables -A INPUT  -p TCP -d " . $ipaddr . " --dport " . $individual_port . " " . $default1);
							break;
						}
						break;
				}

			} // End of port range or individual port
		} // End of port loop
	} // End of check loop
} // End of file loop

file_put_contents("test.rules", $output_lines);

// ut2004 ; INOUT ; aux ; udp ; 7777,7787,7710-7720, 7771



