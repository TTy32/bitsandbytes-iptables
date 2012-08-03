
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


