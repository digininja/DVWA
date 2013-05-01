<?php

/**
 * Validates a URI in CSS syntax, which uses url('http://example.com')
 * @note While theoretically speaking a URI in a CSS document could
 *       be non-embedded, as of CSS2 there is no such usage so we're
 *       generalizing it. This may need to be changed in the future.
 * @warning Since HTMLPurifier_AttrDef_CSS blindly uses semicolons as
 *          the separator, you cannot put a literal semicolon in
 *          in the URI. Try percent encoding it, in that case.
 */
class HTMLPurifier_AttrDef_CSS_URI extends HTMLPurifier_AttrDef_URI
{
    
    public function __construct() {
        parent::__construct(true); // always embedded
    }
    
    public function validate($uri_string, $config, $context) {
        // parse the URI out of the string and then pass it onto
        // the parent object
        
        $uri_string = $this->parseCDATA($uri_string);
        if (strpos($uri_string, 'url(') !== 0) return false;
        $uri_string = substr($uri_string, 4);
        $new_length = strlen($uri_string) - 1;
        if ($uri_string[$new_length] != ')') return false;
        $uri = trim(substr($uri_string, 0, $new_length));
        
        if (!empty($uri) && ($uri[0] == "'" || $uri[0] == '"')) {
            $quote = $uri[0];
            $new_length = strlen($uri) - 1;
            if ($uri[$new_length] !== $quote) return false;
            $uri = substr($uri, 1, $new_length - 1);
        }
        
        $keys   = array(  '(',   ')',   ',',   ' ',   '"',   "'");
        $values = array('\\(', '\\)', '\\,', '\\ ', '\\"', "\\'");
        $uri = str_replace($values, $keys, $uri);
        
        $result = parent::validate($uri, $config, $context);
        
        if ($result === false) return false;
        
        // escape necessary characters according to CSS spec
        // except for the comma, none of these should appear in the
        // URI at all
        $result = str_replace($keys, $values, $result);
        
        return "url($result)";
        
    }
    
}

