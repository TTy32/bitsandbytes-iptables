bitsandbytes-iptables
---------------------

We initiated this project as CSF falled short for our needs. We have virtual network interfaces and we weren't able to apply rules on an IP-basis. Furthermore it is a big disadvatage for us to manage ports with CSF. We have many ports for our gameservers and having to add those to a big one-liner isn't going to cut it. So we decided to write our own IPtables rules. As writing many IPtables rules isnt very efficient this project was created.

# Todo

 * Feature: Advanced rules
 * Feature: Better syntax checking on Rules
 * Feature: Debian INIT script
 * Feature: Specify ALL in ip rules to match any ip address

# Installation

Clone the develop or master branch. The folder processing does all the (..surprise) processing. The bb folder contains all the custom settings. And the init_script folder contains one debian INIT script.

The installation works immediately if you clone the repo in "/etc". This will create a folder named "bitsandbytes-iptables". You can then copy the init script in "/etc/init.d".
Now you can customize all bb files (see usage).

# Usage

As said in installation, the repo contains 3 folders:

## Folder: bb

bb contains all the customizable files.

 * bb-blocklist (here you can enter IP adresses that will be dropped, one per line)
 * bb-conf (doesn''t do anything right now, will be updated)
 * bb-ipaliases (contains a list of semicolon separated alias and ip adresses. You can then use the aliases in bb-rules)
 * bb-iptables_generated (Contains all the generated rules from bb-rules)
 * bb-iptables_post (Contains direct iptables statements that will be executed after bb-iptables_generated)
 * bb-iptables_pre (Contains direct iptables statements that will be executed before bb-iptables_generated)
 * bb-iptables_run (This bash script executes all the iptables statements from 3 files (sourced from above files with prefix bb-iptables_). This file gets also executed in the init script)
 * bb-rules (This file contains a list of custom rules that will be generated to bb-iptables_generated, see explaination below)

## Folder: processing

processing contains all the generation php files.

 * run.php (This file generates bb/bb-iptables_generated from bb/bb-rules)
 * global.include.php (This file auto-loads all class files and filenames.config.php / errordispatcher.php)
 * filenames.config.php (This file contains an associative array defining all bb/ filenames)
 * errordispatcher.php (Errors get dispatched to this file)
 * rule.class.php (rule class, used to construct iptables rules)
 * ruleset.class.php (ruleset class, used to create an array of all the rules
 * parseline.class.php (parseline class, used for early line parsing and trimming)

## Folder: init_script

Here you will find a basic debian init script. Calling this script with "start" executes "processing/run.php" first, to update the rules. Then, "bb/bb-iptables_run" will be executed to apply all iptables rules.

Calling this script with "stop" flushes all the rules and will allow all connections.

Calling this script with "restart" calls start and stop one after another.

# bb-rules file explaination

The rule file consists of line-separeted rules. Each rule consists of semicolon-separated elements. The elements are explained below.

## Semicolon-separated elements

### Description

Description of the rule

### Direction

Allowed directions for the rule

* IN
* OUT
* INOUT

### IP-alias

Alias for the ip-based rule. Must match in file <bb-ipaliases> 

### Protocol

Protocol that applies for the rule

* UDP
* TCP
* BOTH

### Port(s)

Ports that will be allowed. Comma-separated individual ports and comma-separated ranges denoted by a dash supported

## Example

Example rule:

ut2004 ; INOUT ; aux ; udp ; 7777,7787,7710-7720, 7771

A rule with a description "ut2004", allowed IN and OUT, applied to the aux IP alias, matching UDP protocol, allowing port 7777, 7787, the range from 7710 to 7720 and the port 7771


