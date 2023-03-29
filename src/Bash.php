<?php

class Bash
{
    public const DISK_USAGE = "df -h | grep %s | awk '{print $5}'";
    public const MEM_USAGE = "free | grep Mem | awk '{print $3/$2*100}'";
    public const CPU_USAGE = "top -bn1 | grep \"Cpu(s)\" | awk '{print 100 - $8}'";
    public const NET_USAGE = "iftop -t -s 2 -n -P | grep \"Total send and receive\" | awk '{print $8}'";

    public const AST_F2B = "iptables -L f2b-ASTERISK | grep REJECT | wc -l";
    public const AST_IN_USE = "asterisk -rx 'pjsip list endpoints' | grep \"In use\" | wc -l";
    public const AST_UNAVAILABLE = "asterisk -rx 'pjsip list endpoints' | grep \"Unavailable\" | wc -l";
}
