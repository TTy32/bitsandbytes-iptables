#!/usr/bin/env php

<?php

$lines = file ("rules");
foreach ($lines as $key => $value) { // Loop rules (lines)
	$stage1_rules = php_explode (";", $key); // Split by semicolon

	$stage2_rules_description = $stage1_rules[0];
	$stage2_rules_direction = trim ($stage1_rules[1]);
	$stage2_rules_ipalias = trim ($stage1_rules[2]);
	$stage2_rules_protocol = trim ($stage1_rules[3]);
	$stage2_rules_ports = trim ($stage1_rules[4]);
	// TEMPLATE: $stage2_rules_ = trim ($stage1_rules[]);

	$stage2_rules_direction = strtoupper ($stage2_rules_direction);
	$stage2_rules_ipalias = strtoupper ($stage2_rules_ipalias);
	$stage2_rules_protocol = strtoupper ($stage2_rules_protocol);
	$stage2_rules_ports = strtoupper ($stage2_rules_ports);

	
}


// ut2004 ; INOUT ; aux ; udp ; 7777,7787,7710-7720, 7771



