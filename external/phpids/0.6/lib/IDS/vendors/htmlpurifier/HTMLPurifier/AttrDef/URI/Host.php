<?php

/**
 * Validates a host according to the IPv4, IPv6 and DNS (future) specifications.
 */
class HTMLPurifier_AttrDef_URI_Host extends HTMLPurifier_AttrDef
{
    
    /**
     * Instance of HTMLPurifier_AttrDef_URI_IPv4 sub-validator
     */
    protected $ipv4;
    
    /**
     * Instance of HTMLPurifier_AttrDef_URI_IPv6 sub-validator
     */
    protected $ipv6;
    
    public function __construct() {
        $this->ipv4 = new HTMLPurifier_AttrDef_URI_IPv4();
        $this->ipv6 = new HTMLPurifier_AttrDef_URI_IPv6();
    }
    
    public function validate($string, $config, $context) {
        $length = strlen($string);
        if ($string === '') return '';
        if ($length > 1 && $string[0] === '[' && $string[$length-1] === ']') {
            //IPv6
            $ip = substr($string, 1, $length - 2);
            $valid = $this->ipv6->validate($ip, $config, $context);
            if ($valid === false) return false;
            return '['. $valid . ']';
        }
        
        // need to do checks on unusual encodings too
        $ipv4 = $this->ipv4->validate($string, $config, $context);
        if ($ipv4 !== false) return $ipv4;
        
        // A regular domain name.
        
        // This breaks I18N domain names, but we don't have proper IRI support,
        // so force users to insert Punycode. If there's complaining we'll 
        // try to fix things into an international friendly form.
        
        // The productions describing this are:
        $a   = '[a-z]';     // alpha
        $an  = '[a-z0-9]';  // alphanum
        $and = '[a-z0-9-]'; // alphanum | "-"
        // domainlabel = alphanum | alphanum *( alphanum | "-" ) alphanum
        $domainlabel   = "$an($and*$an)?";
        // toplabel    = alpha | alpha *( alphanum | "-" ) alphanum
        $toplabel      = "$a($and*$an)?";
        // hostname    = *( domainlabel "." ) toplabel [ "." ]
        $match = preg_match("/^($domainlabel\.)*$toplabel\.?$/i", $string);
        if (!$match) return false;
        
        return $string;
    }
    
}

