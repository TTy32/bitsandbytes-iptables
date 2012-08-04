bitsandbytes-iptables
#####################

We initiated this project as CSF falled short for our needs. We have virtual network interfaces and we weren't able to apply rules on an IP-basis. Furthermore it is a big disadvatage for us to manage ports with CSF. We have many ports for our gameservers and having to add those to a big one-liner isn't going to cut it. So we decided to write our own IPtables rules. As writing many IPtables rules isnt very efficient this project was created.

# Todo

 * Feature: Advanced rules

# Rule file explaination

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

Alias for the ip-based rule. ALL can be specified for all IP addresses.

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


