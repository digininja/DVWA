<?php

/**
 * Validates nntp (Network News Transfer Protocol) as defined by generic RFC 1738
 */
class HTMLPurifier_URIScheme_nntp extends HTMLPurifier_URIScheme {
    
    public $default_port = 119;
    public $browsable = false;
    
    public function validate(&$uri, $config, $context) {
        parent::validate($uri, $config, $context);
        $uri->userinfo = null;
        $uri->query    = null;
        return true;
    }
    
}

