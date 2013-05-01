<?php

/**
 * Validates http (HyperText Transfer Protocol) as defined by RFC 2616
 */
class HTMLPurifier_URIScheme_http extends HTMLPurifier_URIScheme {
    
    public $default_port = 80;
    public $browsable = true;
    public $hierarchical = true;
    
    public function validate(&$uri, $config, $context) {
        parent::validate($uri, $config, $context);
        $uri->userinfo = null;
        return true;
    }
    
}

